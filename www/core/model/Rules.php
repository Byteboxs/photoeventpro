<?php

namespace app\core\model;

class Rules
{
    private array $rules;
    private $error;

    public function __construct()
    {
        $this->rules = [];
    }

    function required()
    {
        $this->rules[] = new RequiredRule();
        return $this;
    }
    function string()
    {
        $this->rules[] = new TextRule();
        return $this;
    }

    function number()
    {
        $this->rules[] = new NumberRule();
        return $this;
    }

    function email()
    {
        $this->rules[] = new EmailRule();
        return $this;
    }

    function unique($table, $column)
    {
        $msg = 'The value already exists - ' . $column;
        $this->rules[] = new UniqueRule($table, $column, $msg);
        return $this;
    }

    function minLength($min, $msg = 'The field must be at least {min} characters long')
    {
        $msg = str_replace('{min}', $min, $msg);
        $this->rules[] = new MinLengthRule($min, $msg);
        return $this;
    }

    function maxLength($max, $msg = 'The field must be at most {max} characters long')
    {
        $msg = str_replace('{max}', $max, $msg);
        $this->rules[] = new MaxLengthRule($max, $msg);
        return $this;
    }

    function minValue($min, $msg = 'The field must be at least {min}')
    {
        $msg = str_replace('{min}', $min, $msg);
        $this->rules[] = new MinValueRule($min, $msg);
        return $this;
    }

    function maxValue($max, $msg = 'The field must be at most {max}')
    {
        $msg = str_replace('{max}', $max, $msg);
        $this->rules[] = new MaxValueRule($max, $msg);
        return $this;
    }

    function equals($model, $column, $msg = 'The field is not equals to {column}')
    {
        $msg = str_replace('{column}', $column, $msg);
        $this->rules[] = new EqualsRule($model, $column, $msg);
        return $this;
    }

    function get()
    {
        return $this->rules;
    }

    public function validate($value)
    {
        foreach ($this->rules as $rule) {
            if (!$rule->validate($value)) {
                $this->error = $rule->getErrorMessage();
                return true;
            }
        }
        return false;
    }

    public function getErrorMessage()
    {
        return $this->error;
    }
}
