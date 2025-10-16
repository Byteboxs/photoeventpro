<?php

namespace app\core\model;

class MaxValueRule extends Rule implements Approver
{
    private $valid = true;
    public function __construct(int $max, string $errorMessage)
    {
        parent::__construct(self::RULE_MIN_VALUE, '{msg}');
        $this->withCondition('max', $max);
        $this->withCondition('msg', $errorMessage);
    }

    public function validate($value)
    {
        if (is_numeric($value)) {
            $max = $this->conditions->get('max');
            $this->valid = $value <= $max;
        }
        return $this->valid;
    }

    public function getErrorMessage(): string
    {
        return $this->valid === false ? str_replace('{msg}', $this->conditions->get('msg'), $this->errorMessage) : '';
    }
}
