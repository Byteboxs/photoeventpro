<?php

namespace app\core\model;

class MinValueRule extends Rule implements Approver
{
    private $valid = true;
    public function __construct(int $min, string $errorMessage)
    {
        parent::__construct(self::RULE_MIN_VALUE, '{msg}');
        $this->withCondition('min', $min);
        $this->withCondition('msg', $errorMessage);
    }

    public function validate($value)
    {
        if (is_numeric($value)) {
            $min = $this->conditions->get('min');
            $this->valid = $value >= $min;
        }
        return $this->valid;
    }

    public function getErrorMessage(): string
    {
        return $this->valid === false ? str_replace('{msg}', $this->conditions->get('msg'), $this->errorMessage) : '';
    }
}
