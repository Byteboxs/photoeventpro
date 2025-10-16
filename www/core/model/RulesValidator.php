<?php

namespace app\core\model;

use app\core\collections\Map;

class RulesValidator
{
    private $model;
    private $errors;
    private static $instances = [];

    public function __construct()
    {
        $this->model = null;
        $this->errors = new Map();
    }

    public static function make()
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }
        return self::$instances[$className];
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function hasErrors()
    {
        $this->errors->clear();
        $error = false;
        if ($this->model == null) {
            return $error;
        }
        for ($iter = $this->model->getRules()->getIterator(); $iter->hasNext();) {
            $regla = $iter->next();
            $field = $regla->key;
            $rules = $regla->value;
            foreach ($rules as $rule) {
                $hasErrror = $rule->validate($this->model->$field);
                if (!$hasErrror) {
                    $error = true;
                    $this->errors->add($field, $rule->getErrorMessage());
                }
            }
        }
        return $error;
    }

    public function hasError($field)
    {
        return $this->errors->contains($field);
    }

    public function getError($field)
    {
        return $this->errors->get($field);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
