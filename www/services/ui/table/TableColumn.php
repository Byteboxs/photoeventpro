<?php

namespace app\services\ui\table;

class TableColumn
{
    private $name;
    private $width;

    public function __construct($name, $width = '')
    {
        $this->name = $name;
        $this->width = $width;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setWidth(string $width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }
}
