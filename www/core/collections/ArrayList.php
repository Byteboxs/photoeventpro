<?php

namespace app\core\collections;

class ArrayList implements Collection
{
    const SORTED = TRUE;
    const UNSORTED = FALSE;
    private array $array;
    private bool $sorted = self::UNSORTED;

    function __construct(array $array = array())
    {
        $this->array = $array;
    }

    public function add(mixed $objeto): void
    {
        $this->array[] = $objeto;
    }

    public function get(int $index)
    {
        $maxIndex = count($this->array);
        if ($index < $maxIndex && $index >= 0) {
            return $this->array[$index];
        } else {
            return NULL;
        }
    }

    public function clone(): ArrayList
    {
        $clonedArray = [];
        // Realizar una copia profunda de cada elemento en el array
        foreach ($this->array as $element) {
            if (is_object($element) && method_exists($element, '__clone')) {
                $clonedArray[] = clone $element;
            } else {
                $clonedArray[] = $element;
            }
        }
        $clonedArrayList = new self();
        $clonedArrayList->array = $clonedArray;

        return $clonedArrayList;
    }

    public function find(mixed $target, ?Comparable $criteria)
    {
        if ($this->sorted === self::SORTED) {
            echo "<pre>Lista ordenada</pre>";
            if (is_object($target) && method_exists($target, 'compareTo') && $criteria == null) {
                return Algorithm::search($this, $target);
            } else if (!is_object($target) && $criteria !== null) {
                return Algorithm::search($this, $target, $criteria);
            }
        } else {
            echo "<pre>Lista no ordenada</pre>";
            return Algorithm::searchLinear($this, $target, $criteria);
        }
    }

    public function sort(?Comparable $criteria)
    {
        $this->sorted = self::SORTED;
        if ($criteria == null) {
            Algorithm::sort($this);
        } else {
            Algorithm::sort($this, $criteria);
        }
    }

    public function remove(mixed $target, Comparable $criteria)
    {
        if (is_object($target) && method_exists($target, 'compareTo') && $criteria === null) {
            $index = $this->find($target, null);
        } else if (!is_object($target) && $criteria !== null) {
            $index = $this->find($target, $criteria);
        }


        $this->clean();
    }
    public function findString(string $target)
    {
        return $this->find($target, new class implements Comparable
        {
            public function compare($a, $b)
            {
                return strcmp($a, $b);
            }
        });
    }
    public function findNumber($target)
    {
        if (!is_numeric($target)) {
            return -1;
        }
        return $this->find($target, new class implements Comparable
        {
            public function compare($a, $b)
            {
                if ($a > $b) {
                    return 1;
                } else if ($a < $b) {
                    return -1;
                } else {
                    return 0;
                }
            }
        });
    }

    public function addCollection(Collection $collection): void
    {
        for ($iter = $collection->getIterator(); $iter->hasNext();) {
            $this->add($iter->next());
        }
    }

    public function addArray($array)
    {
        foreach ($array as $object) {
            $this->add($object);
        }
    }

    public function addArrayOnTop($array)
    {
        $tmp = array();
        for ($iter = $this->getIterator(); $iter->hasNext();) {
            $obj = $iter->next();
            array_push($tmp, $obj);
        }
        $this->clear();
        $this->addArray($array);
        $this->addArray($tmp);
    }

    public function clear()
    {
        $this->array = array();
    }

    function clean()
    {
        $tmp = array();
        $i = 0;
        for ($iter = $this->getIterator(); $iter->hasNext();) {
            $obj = $iter->next();
            if ($obj != NULL) {
                array_push($tmp, $obj);
            }
            $i++;
        }
        $this->clear();
        $this->addArray($tmp);
    }

    public function contains(...$params)
    {
        $numArgs = func_num_args();
        if ($numArgs === 1) {
            $obj = func_get_arg(0);
            Algorithm::sort($this);
            $index = Algorithm::search($this, $obj);
            return $index !== -1 ? TRUE : FALSE;
        } else if ($numArgs === 2) {
            $obj = func_get_arg(0);
            $comparable = func_get_arg(1);
            Algorithm::sort($this, $comparable);
            $index = Algorithm::search($this, $obj, $comparable);
            return $index !== -1 ? TRUE : FALSE;
        } else {
            return FALSE;
        }
    }

    public function findAll()
    {
        $numArgs = func_num_args();
        $arr = new ArrayList();
        if ($numArgs === 1) {
            $obj = func_get_arg(0);
            Algorithm::sort($this);
            for ($iter = $this->getIterator(); $iter->hasNext();) {
                $element = $iter->next();
                if ($element == $obj) {
                    $arr->add($element);
                }
            }
            return $arr;
        } else if ($numArgs === 2) {
            $obj = func_get_arg(0);
            $comparable = func_get_arg(1);
            Algorithm::sort($this, $comparable);
        } else {
            return FALSE;
        }
    }

    public function isEmpty()
    {
        return count($this->array) == 0 ? TRUE : FALSE;
    }



    public function getIterator()
    {
        return new ArrayListIterator($this->array);
    }



    public function toArray()
    {
        $arr = array();
        for ($iter = $this->getIterator(); $iter->hasNext();) {
            array_push($arr, $iter->next());
        }
        return $arr;
    }

    function length()
    {
        return count($this->array);
    }

    public function __toString()
    {
        $sql = '';
        for ($iter = $this->getIterator(); $iter->hasNext();) {
            $obj = $iter->next();
            $sql .= "[<small>";
            $sql .= "$obj";
            $sql .= "</small>]<br>";
        }
        return $sql;
    }

    public function merge(ArrayList $collection)
    {
        $this->array = array_unique(array_merge($this->array, $collection->toArray()));
        return $this;
    }
}
