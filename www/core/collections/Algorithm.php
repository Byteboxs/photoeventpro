<?php

namespace app\core\collections;

class Algorithm
{

    static function searchLinear($collection, $target, $criteria)
    {
        if ($criteria === null) {
            return Algorithm::linearSearchComparator($collection, $target);
        } else {
            return Algorithm::linearSearch($collection, $target, $criteria);
        }
    }

    private static function linearSearch($collection, $target, $criteria)
    {
        $array = $collection->toArray();
        for ($i = 0; $i < count($array); $i++) {
            if ($criteria->compare($array[$i], $target) === 0) {
                return $i;
            }
        }
    }
    private static function linearSearchComparator($collection, $target)
    {
        $array = $collection->toArray();
        foreach ($array as $key => $value) {
            if ($value->compareTo($target) === 0) {
                return $key;
            }
        }
        return -1;
    }
    static function sort()
    { // IMPLEMENTACION DE QUICKSORT
        $numArgs = func_num_args();
        if ($numArgs === 1) {
            $collection = func_get_arg(0);
            $array = $collection->toArray();
            $sorted = Algorithm::quickSort($array);
            Algorithm::addSortedData($collection, $sorted);
        } else if ($numArgs === 2) {
            $collection = func_get_arg(0);
            $comparable = func_get_arg(1);
            $array = $collection->toArray();
            $sorted = Algorithm::quickSortComparable($array, $comparable);
            Algorithm::addSortedData($collection, $sorted);
        }
    }

    private static function addSortedData($collection, $sorted)
    {
        $collection->clear();
        foreach ($sorted as $data) {
            $collection->add($data);
        }
    }

    private static function quickSort(&$array)
    {
        $loe = $gt = array();
        if (count($array) < 2) {
            return $array;
        }
        $pivot_key = key($array);
        $pivot = array_shift($array);
        foreach ($array as $val) {
            if ($val->compareTo($pivot) == 0) { // SON IGUALES
                $loe[] = $val;
            } else if ($val->compareTo($pivot) > 0) { // OBJ1 ES MAYOR QUE OBJ2
                $gt[] = $val;
            } else if ($val->compareTo($pivot) < 0) { // OBJ1 ES MENOR QUE OBJ2
                $loe[] = $val;
            }
        }
        return array_merge(Algorithm::quickSort($loe), array($pivot_key => $pivot), Algorithm::quickSort($gt));
    }

    private static function quickSortComparable(&$array, $comparable)
    {
        $loe = $gt = array();
        if (count($array) < 2) {
            return $array;
        }
        $pivot_key = key($array);
        $pivot = array_shift($array);
        foreach ($array as $val) {
            if ($comparable->compare($val, $pivot) == 0) { // SON IGUALES
                $loe[] = $val;
            } else if ($comparable->compare($val, $pivot) > 0) { // OBJ1 ES MAYOR QUE OBJ2
                $gt[] = $val;
            } else if ($comparable->compare($val, $pivot) < 0) { // OBJ1 ES MENOR QUE OBJ2
                $loe[] = $val;
            }
        }
        return array_merge(Algorithm::quickSortComparable($loe, $comparable), array($pivot_key => $pivot), Algorithm::quickSortComparable($gt, $comparable));
    }

    static function search()
    {
        $numArgs = func_num_args();
        if ($numArgs === 2) {
            $collection = func_get_arg(0);
            $secret = func_get_arg(1);
            $array = $collection->toArray();
            $start = 0;
            $end = (count($array) - 1);
            return Algorithm::binarySearchIterative($array, $secret, $start, $end);
        } else if ($numArgs === 3) {
            $collection = func_get_arg(0);
            $secret = func_get_arg(1);
            $comparable = func_get_arg(2);
            $array = $collection->toArray();
            $start = 0;
            $end = (count($array) - 1);
            $index = Algorithm::binarySearchIterativeComparable($array, $secret, $start, $end, $comparable);
            return $index;
        }
    }

    private static function binarySearchIterative($array, $secret, $start, $end)
    {
        while ($start <= $end) {
            $guess = (int) ($start + (($end - $start) / 2));
            if ($array[$guess]->compareTo($secret) === 0) {
                return (int) $guess;
            } else if ($array[$guess]->compareTo($secret) > 0) {
                $end = $guess - 1;
            } else if ($array[$guess]->compareTo($secret) < 0) {
                $start = $guess + 1;
            }
        }
        return -1;
    }
    private static function binarySearchIterativeComparable($array, $secret, $start, $end, $comparable)
    {
        while ($start <= $end) {
            $guess = (int) ($start + (($end - $start) / 2));
            if ($comparable->compare($array[$guess], $secret) === 0) {
                return (int) $guess;
            } else if ($comparable->compare($array[$guess], $secret) > 0) {
                $end = $guess - 1;
            } else if ($comparable->compare($array[$guess], $secret) < 0) {
                $start = $guess + 1;
            }
        }
        return -1;
    }
}
