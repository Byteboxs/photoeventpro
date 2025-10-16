<?php

namespace app\core\database\builder;

class JoinClause
{
    const INNER = 'INNER';
    const LEFT_JOIN = 'LEFT JOIN';
    const RIGHT_JOIN = 'RIGHT JOIN';
    const FULL_JOIN = 'FULL JOIN';
    private string $joinType;
    private array $joinList;
    private Join $join;

    public function __construct(string $joinType = self::INNER)
    {
        $this->joinType = $joinType;
        $this->joinList = [];
    }

    public function join(string $table1, string $table2)
    {
        $join = null;
        switch ($this->joinType) {
            case self::INNER:
                $join = new InnerJoin($table1, $table2);
                break;
            case self::LEFT_JOIN:
                $join = new LeftJoin($table1, $table2);
                break;
            case self::RIGHT_JOIN:
                $join = new RightJoin($table1, $table2);
                break;
            case self::FULL_JOIN:
                $join = new FullJoin($table1, $table2);
                break;
        }
        $this->joinList[] = $join;
        $this->join = $join;
        return $join;
    }

    public function render(): string
    {
        return implode('', $this->joinList);
    }

    public function __toString()
    {
        return $this->render();
    }
}
