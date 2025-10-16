<?php

namespace core\database;

class whereBuilder
{
    private $params;
    private $andConditions;
    /**
     * Initializes a new instance of the class.
     *
     * @param \core\collections\ArrayList $params The list of parameters.
     */
    public function __construct(\core\collections\ArrayList $params)
    {
        $this->andConditions = new \core\collections\ArrayList();
        $this->params = $params;
    }

    /**
     * Builds a prepared WHERE fragment based on the given arguments.
     *
     * @param array $arg The array of arguments. It should contain either 2 or 3 elements.
     *                  If there are 3 elements, they should be in the order of column,
     *                  operator, and value. If there are 2 elements, they should be in
     *                  the order of column and value.
     * @throws \InvalidArgumentException If the number of arguments is not 2 or 3.
     * @return string The prepared WHERE fragment.
     */
    public function buildPreparedWhereFragment(array $arg): string
    {
        if (!empty($arg) && is_array($arg)) {
            if (count($arg) === 3) {
                list($column, $operator, $value) = $arg;
                $this->params->add($value);
                return "$column $operator ?";
            } elseif (count($arg) === 2) {
                list($column, $value) = $arg;
                $this->params->add($value);
                return "$column = ?";
            } else {
                throw new \InvalidArgumentException("El numero de argumentos debe ser 2 o 3.");
            }
        }
        return '';
    }

    /**
     * Builds a WHERE clause fragment based on the given arguments.
     *
     * @param array $arg The array of arguments. It should contain either 2 or 3 elements.
     *                   If 2 elements are provided, the first element represents the column name
     *                   and the second element represents the value to be compared with.
     *                   If 3 elements are provided, the first element represents the column name,
     *                   the second element represents the operator for comparison, and the third
     *                   element represents the value to be compared with.
     * @throws \InvalidArgumentException If the number of elements in the array is neither 2 nor 3.
     * @return string The WHERE clause fragment generated based on the given arguments.
     */
    public function buildWhereFragment(array $arg): string
    {
        if (!empty($arg) && is_array($arg)) {
            if (count($arg) === 3) {
                list($column, $operator, $value) = $arg;
                return "$column $operator $value";
            } elseif (count($arg) === 2) {
                list($column, $value) = $arg;
                return "$column = $value";
            } else {
                throw new \InvalidArgumentException("El numero de argumentos debe ser 2 o 3.");
            }
        }
        return '';
    }

    /**
     * Generates a function comment for the given function body.
     *
     * @param mixed ...$args the arguments passed to the function
     *
     * @throws \InvalidArgumentException if the number of arguments is not 1, 2, or 3
     *
     * @return self returns an instance of the class
     */
    public function where(...$args): self
    {
        $numArgs = count($args);
        if ($numArgs === 1) {
            if (is_array($args[0])) {
                foreach ($args[0] as $arg) {
                    $this->andConditions->add($this->buildPreparedWhereFragment($arg));
                }
            } else {
                throw new \InvalidArgumentException("Si es un array no debe estar vacio.");
            }
        } elseif ($numArgs === 2) {
            if (is_callable($args[0])) {
                $builder = new \core\database\Builder();
                call_user_func($args[0], $builder);
                $this->addSubqueryCondition("'" . $args[1] . "' = (" . $builder->get() . ")", $builder->getParams());
            } else {
                $this->andConditions->add($this->buildPreparedWhereFragment($args));
            }
        } else if ($numArgs === 3) {
            if (is_callable($args[2])) {
                $builder = new \core\database\Builder();
                call_user_func($args[2], $builder);
                $this->addSubqueryCondition($args[0] . " " . $args[1] . " " . "(" . $builder->get() . ")", $builder->getParams());
            } else {
                $this->andConditions->add($this->buildPreparedWhereFragment($args));
            }
        } else {
            throw new \InvalidArgumentException("El método where acepta 2 o 3 argumentos.");
        }
        return $this;
    }

    /**
     * Adds a subquery condition to the current query.
     *
     * @param string $condition The subquery condition to add.
     * @param array $params An array of parameters to bind to the subquery condition.
     * @return void
     */
    private function addSubqueryCondition(string $condition, array $params): void
    {
        $this->andConditions->add($condition);
        $this->params->addArray($params);
    }

    /**
     * A method that adds a WHERE clause to the query based on the given column(s).
     *
     * @param mixed ...$args The column(s) to add to the WHERE clause.
     * @throws \InvalidArgumentException If the number of arguments is not 1, 2, or 3.
     * @return self Returns the current instance for chaining.
     */
    public function whereColumn(...$args): self
    {
        $numArgs = count($args);
        if ($numArgs === 1) {
            if (is_array($args[0])) {
                foreach ($args[0] as $arg) {
                    $this->andConditions->add($this->buildWhereFragment($arg));
                }
            } else {
                throw new \InvalidArgumentException("Si es un array no debe estar vacio.");
            }
        } elseif ($numArgs === 2 || $numArgs === 3) {
            $this->andConditions->add($this->buildWhereFragment($args));
        } else {
            throw new \InvalidArgumentException("El método where acepta 2 o 3 argumentos.");
        }
        return $this;
    }

    /**
     * Adds a raw condition to the query.
     *
     * @param string $condition The raw condition to add.
     * @param array|null $params The parameters to bind to the condition.
     * @return self Returns the instance of the class for method chaining.
     */
    public function whereRaw(string $condition, ?array $params = null): self
    {
        $this->andConditions->add($condition);
        if ($params !== null) {
            $this->params->addArray($params);
        }
        return $this;
    }

    /**
     * A method that adds a "WHERE IN" condition to the query.
     *
     * @param string $column The column name to perform the "WHERE IN" condition on.
     * @param array $data An array of values to be used in the "WHERE IN" condition.
     * @return self Returns an instance of the class to allow for method chaining.
     */
    public function whereIn(string $column, array $data): self
    {
        if (!empty($data)) {
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $this->andConditions->add("$column IN ($placeholders)");
            $this->params->addArray($data);
        }
        return $this;
    }

    /**
     * Generates a WHERE NOT IN clause for the SQL query.
     *
     * @param string $column The column name to check for the NOT IN condition.
     * @param array $data An array of values to check against the column.
     * @return self Returns the current instance for method chaining.
     */
    public function whereNotIn(string $column, array $data): self
    {
        if (!empty($data)) {
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $this->andConditions->add("$column NOT IN ($placeholders)");
            $this->params->addArray($data);
        }
        return $this;
    }

    /**
     * Adds a WHERE clause to the given query string if there are existing AND conditions and the query string does not already contain a WHERE clause.
     *
     * @param string $query The query string to add the WHERE clause to.
     * @throws \Exception If an error occurs.
     * @return string The modified query string.
     */
    public function addWhere(string $query): string
    {
        if (!$this->andConditions->isEmpty() && !str_contains(strtoupper($query), 'WHERE')) {
            return " WHERE ";
        }
        return '';
    }
    public function getWhereStatement(): string
    {
        return implode(' AND ', $this->andConditions->toArray());
        // $sql = '';
        // if (!$this->andConditions->isEmpty()) {
        //     $sql .= implode(' AND ', $this->andConditions->toArray());
        // }
        // return $sql;
    }
}
