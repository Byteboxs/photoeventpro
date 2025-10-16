<?php

namespace app\core\collections;

interface Collection extends Container
{
    // function add(...$params);
    // function add(mixed $objeto): void;
    // function addCollection($collection);
    function clear();
    // function contains(...$params);
    function isEmpty();
    // function remove(...$params);
    function toArray();
}
