<?php

namespace app\core\model;

use app\core\collections\ArrayList;
use app\core\collections\Map;

class Validator
{
    protected Map $rules;
    protected Map $errors;
    protected Map $errorMessages;
    protected Map $data;

    private static $instances = [];

    private array $safe = [];

    protected function __construct(array $data, array $rules)
    {
        $this->rules = new Map();
        $this->errors = new Map();
        $this->errorMessages = new Map();
        $this->data = new Map($data);
        $this->setRules($rules);
        $this->validate();
    }

    public static function make(array $data, array $rules)
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static($data, $rules);
        }
        return self::$instances[$className];
    }

    protected function setRules(array $rules)
    {
        foreach ($rules as $key => $value) {
            $this->addRule($key, $this->stringToRules($value));
        }
    }

    protected function addRule(string $key, array $rules = []): void
    {
        $this->rules->put($key, $rules);
    }

    private function stringToRules(string $rawRules)
    {
        $commands = explode('|', $rawRules);
        $rules = new Rules();
        foreach ($commands as $command) {
            $parts = explode(':', $command);
            $action = $parts[0];
            if ($action == 'required') {
                $rules->required();
            } else if ($action == 'email') {
                $rules->email();
            } else if ($action == 'string') {
                $rules->string();
            } else if ($action == 'integer') {
                $rules->number();
            } else if ($action == 'number') {
                $rules->number();
            } else if ($action == 'minValue') {
                $rules->minValue($parts[1]);
            } else if ($action == 'maxValue') {
                $rules->maxValue($parts[1]);
            } else if ($action == 'minLength') {
                $rules->minLength($parts[1]);
            } else if ($action == 'maxLength') {
                $rules->maxLength($parts[1]);
            } else if ($action == 'equals') {
                $rules->equals($parts[1], $parts[2]);
            } else if ($action == 'unique') {
                $parts = explode(',', $parts[1]);
                $rules->unique($parts[0], $parts[1]);
            }
        }
        return $rules->get();
    }

    private function validate()
    {
        for ($iter = $this->rules->getIterator(); $iter->hasNext();) {
            $fail = false;
            $current = $iter->next();
            $attribute = $current->key;
            $rules = $current->value;
            $value = $this->data->get($attribute);
            foreach ($rules as $rule) {
                if (!$rule->validate($value)) {
                    $fail = true;
                    $this->addError($attribute, $rule->getErrorMessage());
                }
            }
            if (!$fail) {
                $this->safe[$attribute] = $value;
            }
        }
    }

    public function validated()
    {
        return $this->safe;
    }

    private function addError($attribute, $message)
    {
        if ($this->errors->contains($attribute)) {
            $messages = $this->errors->get($attribute);
            $messages[] = $message;
        } else {
            $messages[] = $message;
        }
        $this->errors->put($attribute, $messages);
    }

    public function fails()
    {
        return !$this->errors->isEmpty();
    }

    public function hasError($attribute)
    {
        return $this->errors->contains($attribute);
    }

    public function getErrors($attribute)
    {
        if ($this->errors->contains($attribute)) {
            return $this->errors->get($attribute);
        }
        return false;
    }

    public function getFirstError($attribute): string
    {
        if ($this->errors->contains($attribute)) {
            return $this->errors->get($attribute)[0];
        }
        return '';
    }
}
