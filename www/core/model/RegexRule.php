<?php

namespace app\core\model;

class RegexRule extends Rule implements Approver
{
    private $valid = true;
    public function __construct(string $pattern, string $errorMessage)
    {
        parent::__construct(self::RULE_REGEX, '{msg}');
        $this->withCondition('pattern', $pattern);
        $this->withCondition('msg', $errorMessage);
    }

    public function validate($value)
    {
        $pattern = $this->conditions->get('pattern');
        $this->valid = !preg_match($pattern, $value) ? false : true;
        return $this->valid;
    }

    public function getErrorMessage(): string
    {
        return $this->valid === false ? str_replace('{msg}', $this->conditions->get('msg'), $this->errorMessage) : '';
    }
}
