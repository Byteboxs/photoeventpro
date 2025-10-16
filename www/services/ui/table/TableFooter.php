<?php

namespace app\services\ui\table;

class TableFooter
{
    private array $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}
