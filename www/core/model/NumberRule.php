<?php

namespace app\core\model;

class NumberRule extends RegexRule
{
    public function __construct()
    {
        parent::__construct("/^[0-9]+$/", 'Must be number');
    }
}
