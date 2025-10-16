<?php

namespace app\core\model;

class MinLengthRule extends Rule implements Approver
{
    private $valid = true;
    public function __construct(int $length, string $errorMessage)
    {
        parent::__construct(self::RULE_MIN_LENGTH, '{msg}');
        $this->withCondition('length', $length);
        $this->withCondition('msg', $errorMessage);
    }

    public function validate($value)
    {
        $length = $this->conditions->get('length');
        $this->valid = strlen($value) < $length ? false : true;
        return $this->valid;
    }

    public function getErrorMessage(): string
    {
        return $this->valid === false ? str_replace('{msg}', $this->conditions->get('msg'), $this->errorMessage) : '';
    }
}
