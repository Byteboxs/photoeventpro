<?php

namespace app\core\collections;

class ArrayListIterator implements MyIterator
{
    private $index;
    private $array;

    function __construct(&$array)
    {
        $this->index = 0;
        $this->array = $array;
    }

    public function hasNext()
    {
        $count = count($this->array);
        if ($this->index < $count) {
            return true;
        }
        return false;
    }

    public function next()
    {
        if ($this->hasNext()) {
            return $this->array[$this->index++];
        }
        return null;
    }
}
