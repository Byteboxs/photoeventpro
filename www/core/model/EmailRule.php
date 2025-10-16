<?php

namespace app\core\model;

class EmailRule extends RegexRule
{
    public function __construct()
    {
        parent::__construct('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', 'Invalid email address');
    }
}
