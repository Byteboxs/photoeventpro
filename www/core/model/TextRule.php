<?php

namespace app\core\model;

class TextRule extends RegexRule
{
    public function __construct()
    {
        parent::__construct("/^[a-zA-Z]+$/", 'Must be a string of characters');
    }
}
