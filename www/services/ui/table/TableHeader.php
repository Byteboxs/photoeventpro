<?php

namespace app\services\ui\table;

class TableHeader
{
    private array $columns;

    public function __construct(array $columns = [])
    {
        $this->columns = $columns;
    }

    public function addColumn(string $column, string $width = ''): self
    {
        $this->columns[] = new TableColumn($column, $width);
        return $this;
    }

    public function addAllColumns(array $columns): self
    {
        foreach ($columns as $column) {
            if (is_array($column)) {
                $this->addColumn($column[0], $column[1]);
            } else {
                $this->addColumn($column);
            }
        }
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}
