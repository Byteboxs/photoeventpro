<?php

namespace app\services\ui\table;

class TableConfig
{
    private string $tableClasses = 'table';
    private $attributes;

    public function setTableClasses(string $classes): self
    {
        $this->tableClasses = $classes;
        $this->attributes = [];
        return $this;
    }

    public function setAttribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttributes(): string
    {
        $html = '';
        foreach ($this->attributes as $key => $value) {
            $html .= sprintf(' %s="%s"', $key, $value);
        }
        return $html;
    }

    public function getTableClasses(): string
    {
        return $this->tableClasses;
    }
}
