<?php

namespace app\core\model;

class EqualsRule  extends Rule implements Approver
{
    private $valid = true;
    public function __construct(object $class, string $field, string $errorMessage)
    {
        parent::__construct(self::RULE_EQUALS, 'Error, the fields do not match. {msg}');
        $this->withCondition('class', $class);
        $this->withCondition('field', $field);
        $this->withCondition('msg', $errorMessage);
    }

    public function validate($value)
    {
        $class = $this->conditions->get('class');
        $field = $this->conditions->get('field');

        if ($class !== null && property_exists($class, $field) && $value != $class->$field) {
            $this->valid = false;
        }

        return $this->valid;
    }

    public function getErrorMessage()
    {
        return $this->valid === false ? str_replace('{msg}', $this->conditions->get('msg'), $this->errorMessage) : '';
    }
}
