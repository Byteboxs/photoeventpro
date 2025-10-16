<?php

namespace app\core\model;

use app\core\collections\Map;

abstract class Rule
{
    const RULE_REGEX = 'regex';
    const RULE_REQUIRED = 'required';
    const RULE_UNIQUE = 'unique';
    const RULE_EQUALS = 'equals';
    const RULE_MIN_LENGTH = 'minLength';
    const RULE_MIN_VALUE = 'minValue';
    const RULE_MAX_LENGTH = 'maxLength';
    const RULE_MAX_CALUE = 'maxValue';
    protected string $ruleName;
    protected Map $conditions;

    protected string $errorMessage = '';

    public function __construct(string $ruleName, $errorMessage = '')
    {
        $this->ruleName = $ruleName;
        $this->conditions = new Map();
        $this->errorMessage = $errorMessage;
    }

    public function withCondition(string $condition, $value): Rule
    {
        $this->conditions->put($condition, $value);
        return $this;
    }
}
