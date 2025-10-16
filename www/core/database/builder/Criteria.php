<?php

namespace app\core\database\builder;

class Criteria
{
    private string $expression;
    private ParametersBuilder $parameters;

    /**
     * Constructor de Criteria.
     *
     * @param string $column Columna a comparar (opcional, para construcción fluida).
     * @param string $operator Operador de comparación (opcional, para construcción fluida).
     * @param mixed $value Valor a comparar (opcional, para construcción fluida).
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     */
    public function __construct(string $column = '', string $operator = '', $value = '', bool $useParameter = true) // ¡NUEVO PARÁMETRO $useParameter!
    {
        $this->parameters = new ParametersBuilder();

        if (!empty($column) && !empty($operator)) {
            if ($useParameter) { // ¡Usa parametrización solo si $useParameter es true!
                $paramName = str_replace('.', '_', $column) . '_' . uniqid();
                $this->expression = "$column $operator :{$paramName}";
                $this->parameters->addParameter($paramName, $value);
            } else {
                $this->expression = "$column $operator '$value'"; // No parametriza si $useParameter es false
            }
        } else {
            $this->expression = '';
        }
    }

    // static method create as constructor
    public static function create(string $column = '', string $operator = '', $value = '', bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, $operator, $value, $useParameter); // Pasa $useParameter al constructor
    }


    /**
     * Crea un Criteria para la condición de igualdad (=).
     *
     * @param string $column Nombre de la columna.
     * @param mixed $value Valor a comparar.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function equals(string $column, $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, '=', $value, $useParameter); // Pasa $useParameter al constructor
    }

    /**
     * Crea un Criteria para la condición de desigualdad (!=).
     *
     * @param string $column Nombre de la columna.
     * @param mixed $value Valor a comparar.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function notEquals(string $column, $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, '!=', $value, $useParameter);
    }

    /**
     * Crea un Criteria para la condición "mayor que" (>).
     *
     * @param string $column Nombre de la columna.
     * @param mixed $value Valor a comparar.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function greaterThan(string $column, $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, '>', $value, $useParameter);
    }

    /**
     * Crea un Criteria para la condición "menor que" (<).
     *
     * @param string $column Nombre de la columna.
     * @param mixed $value Valor a comparar.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function lessThan(string $column, $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, '<', $value, $useParameter);
    }

    /**
     * Crea un Criteria para la condición "mayor o igual que" (>=).
     *
     * @param string $column Nombre de la columna.
     * @param mixed $value Valor a comparar.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function greaterThanOrEqual(string $column, $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, '>=', $value, $useParameter);
    }

    /**
     * Crea un Criteria para la condición "menor o igual que" (<=).
     *
     * @param string $column Nombre de la columna.
     * @param mixed $value Valor a comparar.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function lessThanOrEqual(string $column, $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, '<=', $value, $useParameter);
    }

    /**
     * Crea un Criteria para la condición LIKE.
     *
     * @param string $column Nombre de la columna.
     * @param string $value Patrón LIKE.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function like(string $column, string $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, 'LIKE', $value, $useParameter);
    }

    /**
     * Crea un Criteria para la condición NOT LIKE.
     *
     * @param string $column Nombre de la columna.
     * @param string $value Patrón LIKE.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function notLike(string $column, string $value, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        return new self($column, 'NOT LIKE', $value, $useParameter);
    }

    /**
     * Crea un Criteria para la condición BETWEEN.
     *
     * @param string $column Nombre de la columna.
     * @param mixed $value1 Valor inicial del rango.
     * @param mixed $value2 Valor final del rango.
     * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
     * @return self
     */
    public static function between(string $column, $value1, $value2, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    {
        $criteria = new self('', '', '', $useParameter); // Pasa $useParameter al constructor

        $param1 = str_replace('.', '_', $column) . '_b1_' . uniqid();
        $param2 = str_replace('.', '_', $column) . '_b2_' . uniqid();

        $criteria->expression = "$column BETWEEN :$param1 AND :$param2";
        $criteria->parameters->addParameter($param1, $value1);
        $criteria->parameters->addParameter($param2, $value2);

        return $criteria;
    }

    /**
     * Crea un Criteria para la condición IS NULL.
     *
     * @param string $column Nombre de la columna.
     * @return self
     */
    public static function isNull(string $column): self
    {
        return new self($column, 'IS', 'NULL', false); // ¡Parametrización siempre false para IS NULL/IS NOT NULL!
    }

    /**
     * Crea un Criteria para la condición IS NOT NULL.
     *
     * @param string $column Nombre de la columna.
     * @return self
     */
    public static function isNotNull(string $column): self
    {
        return new self($column, 'IS NOT', 'NULL', false); // ¡Parametrización siempre false para IS NULL/IS NOT NULL!
    }

    // /**
    //  * Crea un Criteria para la condición EXISTS subconsulta.
    //  *
    //  * @param Select $subquery Subconsulta Select.
    //  * @return self
    //  */
    // public static function exists(Select $subquery): self
    // {
    //     return new self('', 'EXISTS', '(' . $subquery->build() . ')', false); // ¡Parametrización siempre false para subqueries!
    // }

    // /**
    //  * Crea un Criteria para la condición NOT EXISTS subconsulta.
    //  *
    //  * @param Select $subquery Subconsulta Select.
    //  * @return self
    //  */
    // public static function notExists(Select $subquery): self
    // {
    //     return new self('', 'NOT EXISTS', '(' . $subquery->build() . ')', false); // ¡Parametrización siempre false para subqueries!
    // }

    // /**
    //  * Crea un Criteria para la condición IN.
    //  *
    //  * @param string $column Nombre de la columna.
    //  * @param array|Select $values Array de valores o subconsulta Select.
    //  * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
    //  * @return self
    //  */
    // public static function in(string $column, array|Select $values, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    // {
    //     $criteria = new self('', '', '', $useParameter); // Pasa $useParameter al constructor

    //     if ($values instanceof Select) {
    //         $criteria->expression = "$column IN (" . $values->build() . ")";
    //         foreach ($values->getParameters() as $paramName => $paramValue) {
    //             $criteria->parameters->addParameter($paramName, $paramValue);
    //         }
    //     } else {
    //         $placeholders = [];
    //         foreach ($values as $index => $value) {
    //             $paramName = str_replace('.', '_', $column) . "_in_" . uniqid() . "_{$index}";
    //             $placeholders[] = ":{$paramName}";
    //             $criteria->parameters->addParameter($paramName, $value);
    //         }
    //         $criteria->expression = "$column IN (" . implode(', ', $placeholders) . ")";
    //     }

    //     return $criteria;
    // }

    // /**
    //  * Crea un Criteria para la condición NOT IN.
    //  *
    //  * @param string $column Nombre de la columna.
    //  * @param array|Select $values Array de valores o subconsulta Select.
    //  * @param bool $useParameter Indica si se debe parametrizar el valor (opcional, por defecto true). ¡NUEVO PARÁMETRO!
    //  * @return self
    //  */
    // public static function notIn(string $column, array|Select $values, bool $useParameter = true): self // ¡NUEVO PARÁMETRO $useParameter!
    // {
    //     $criteria = new self('', '', '', $useParameter); // Pasa $useParameter al constructor

    //     if ($values instanceof Select) {
    //         $criteria->expression = "$column NOT IN (" . $values->build() . ")";
    //         foreach ($values->getParameters() as $paramName => $paramValue) {
    //             $criteria->parameters->addParameter($paramName, $paramValue);
    //         }
    //     } else {
    //         $placeholders = [];
    //         foreach ($values as $index => $value) {
    //             $paramName = str_replace('.', '_', $column) . "_notin_" . uniqid() . "_{$index}";
    //             $placeholders[] = ":{$paramName}";
    //             $criteria->parameters->addParameter($paramName, $value);
    //         }
    //         $criteria->expression = "$column NOT IN (" . implode(', ', $placeholders) . ")";
    //     }

    //     return $criteria;
    // }

    public function and(Criteria $criteria): self
    {
        if ($this->expression === '') return $criteria;
        if ($criteria->getExpression() === '') return $this;

        $result = new self();
        $result->expression = "({$this->expression} AND {$criteria->getExpression()})";
        $result->parameters = new ParametersBuilder();
        foreach ($this->parameters->getParameters() as $key => $value) {
            $result->parameters->addParameter($key, $value);
        }
        foreach ($criteria->getParameters() as $key => $value) {
            $result->parameters->addParameter($key, $value);
        }

        return $result;
    }

    public function or(Criteria $criteria): self
    {
        if ($this->expression === '') return $criteria;
        if ($criteria->getExpression() === '') return $this;

        $result = new self();
        $result->expression = "({$this->expression} OR {$criteria->getExpression()})";
        $result->parameters = new ParametersBuilder();
        foreach ($this->parameters->getParameters() as $key => $value) {
            $result->parameters->addParameter($key, $value);
        }
        foreach ($criteria->getParameters() as $key => $value) {
            $result->parameters->addParameter($key, $value);
        }

        return $result;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function __tostring(): string
    {
        return $this->expression;
    }

    public function getParameters(): array
    {
        return $this->parameters->getParameters();
    }
}
