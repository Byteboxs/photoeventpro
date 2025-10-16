<?php

namespace core\trash;

class View
{
    private $engine;
    private $data;
    private $name;

    public function __construct($name, $data = [])
    {
        $this->engine = new Engine();
        foreach ($data as $key => $value) {
            $this->with($key, $value);
        }
        $this->name = $name;
    }

    //create function getNasme
    public function getName()
    {
        return $this->name;
    }

    public function getData()
    {
        return $this->data;
    }

    public function with($key, $value = null)
    {
        //crete if $value instace of View, call render
        if ($value instanceof View) {
            $this->data[$key] = $this->engine->get($value->getName(), $value->getData());
        } else {
            if (is_array($key)) {
                $this->data = array_merge($this->data, $key);
            } else {
                $this->data[$key] = $value;
            }
        }

        return $this;
    }

    public function render()
    {
        return $this->engine->get($this->name, $this->data);
    }
}
