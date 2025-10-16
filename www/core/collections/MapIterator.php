<?php

namespace app\core\collections;

class MapIterator implements MyIterator
{
    private $index;
    private $array;
    private $keys;

    function __construct(&$array)
    {
        $this->index = 0;
        $this->array = $array;
        $this->keys = array_keys($array);
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function hasNext()
    {
        return isset($this->keys[$this->index]);
    }

    public function next()
    {
        if ($this->hasNext()) {
            $key = $this->keys[$this->index++];
            return (object) [
                'key' => $key,
                'value' => $this->array[$key]
            ];
        }
        return null;
    }
}
