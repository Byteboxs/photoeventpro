<?php

namespace app\core\collections;

// Ejemplo de uso del iterador 
//for ($iter = $this->constraints->getIterator(); $iter->hasNext();) {
//     echo '<pre>';
//     var_dump($iter->next());
//     echo '</pre>';
// }
// $iter = $this->constraints->getIterator();
// while ($iter->hasNext()) {
//     echo '<pre>';
//     var_dump($iter->next());
//     echo '</pre>';
// }

class Map implements Collection, Container
{
    private $map;

    public function __construct(array $array = [])
    {
        $this->map = $array;
    }

    public function getIterator()
    {
        return new MapIterator($this->map);
    }

    public function add(...$params)
    {
        $numParams = count($params);
        if ($numParams === 2) {
            $key = $params[0];
            $value = $params[1];
            if (is_null($key)) {
                $this->map[] = $value;
            } else {
                $this->map[$key] = $value;
            }
        } else {
            throw new \InvalidArgumentException("The Map::add method accepts 2 arguments.");
        }
    }

    public function put($key, $value)
    {
        $this->map[$key] = $value;
    }

    public function addArray($array)
    {
        foreach ($array as $key => $value) {
            $this->add($key, $value);
        }
    }

    public function addCollection($collection)
    {
    }

    public function merge(Map $map)
    {
        foreach ($map->toArray() as $key => $value) {
            if ($this->contains($key)) {
                $this->remove($key);
            }
            $this->add($key, $value);
        }
    }

    public function clear()
    {
        $this->map = [];
    }

    public function contains(...$params)
    {
        $key = $params[0];
        return array_key_exists($key, $this->map);
    }

    public function isEmpty()
    {
        return empty($this->map);
    }

    public function remove(...$key)
    {
        $key = $key[0];
        unset($this->map[$key]);
    }

    public function toArray()
    {
        return $this->map;
    }

    public function __toString()
    {
        return json_encode($this->map);
    }

    public function get($key)
    {
        return $this->map[$key];
    }

    public function getOrDefault($key, $default = null)
    {
        if ($this->contains($key)) {
            return $this->map[$key];
        } else {
            return $default;
        }
    }

    public function set($key, $value)
    {
        if ($this->contains($key)) {
            $this->map[$key] = $value;
        }
    }
}
