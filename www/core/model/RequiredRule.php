<?php

namespace app\core\model;

class RequiredRule extends Rule
{
    public function __construct()
    {
        parent::__construct(Rule::RULE_REQUIRED, 'This field is required');
    }

    public function validate($value)
    {
        return !$value ? false : true;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
