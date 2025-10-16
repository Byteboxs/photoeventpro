<?php

namespace app\core\collections;

use Iterator;
use SplDoublyLinkedList;

class LinkedBag implements Collection, Iterator
{
    private $linkedList;
    private int $position;

    public function __construct()
    {
        $this->linkedList = new SplDoublyLinkedList();
        $this->position = 0;
    }

    public function addCollection($collection)
    {
        foreach ($collection->toArray() as $value) {
            $this->add($value);
        }
    }

    public function add(...$params)
    {
        if (count($params) === 1) {
            $value = $params[0];
        } else {
            throw new \InvalidArgumentException('Invalid number of arguments');
        }
        $this->linkedList->push($value);
    }

    public function remove(...$args)
    {
        if (count($args) === 1) {
            $value = $args[0];
            $index = $this->search($value);
            if ($index !== false) {
                $this->linkedList->offsetUnset($index);
            }
        }
    }
    /**
     * @param mixed $value
     * @param Comparable|null $comparable
     * @return int|bool
     */
    private function search(mixed $value, Comparable $comparable = null): int|bool
    {
        foreach ($this->linkedList as $key => $item) {
            if ($comparable === null && $item === $value) {
                return $key;
            } elseif ($comparable !== null && $comparable->compare($item, $value) === 0) {
                return $key;
            }
        }

        return false;
    }
    public function clear()
    {
        $this->linkedList = new SplDoublyLinkedList();
    }
    /**
     * Check if the collection contains a specific value.
     *
     * @param mixed $value The value to search for.
     * @return bool True if the collection contains the value, false otherwise.
     * @throws \InvalidArgumentException If an invalid number of arguments is provided.
     */
    public function contains(...$args)
    {
        if (count($args) === 1) {
            $value = $args[0];
            return $this->search($value) !== false;
        } else {
            throw new \InvalidArgumentException('Invalid number of arguments');
        }
    }

    public function isEmpty()
    {
        return $this->linkedList->isEmpty();
    }
    public function toArray()
    {
        $result = [];
        foreach ($this->linkedList as $value) {
            $result[] = $value;
        }
        return $result;
    }

    function rewind(): void
    {
        $this->position = 0;
    }

    function current()
    {
        return $this->linkedList->offsetGet($this->position);
    }

    function key()
    {
        return $this->position;
    }

    function next(): void
    {
        $this->position++;
    }

    function valid(): bool
    {
        return $this->linkedList->offsetExists($this->position);
    }
}
