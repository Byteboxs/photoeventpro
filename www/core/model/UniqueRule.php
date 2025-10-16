<?php

namespace app\core\model;

use app\core\Application;

class UniqueRule extends Rule
{
    private $valid = true;
    public function __construct(string $table, string $column, string $errorMessage)
    {
        parent::__construct(Rule::RULE_UNIQUE, '{msg}');
        $this->withCondition('table', $table);
        $this->withCondition('column', $column);
        $this->withCondition('msg', $errorMessage);
    }

    public function validate($value)
    {
        $builder = Application::$app->builder;
        $record = $builder->table($this->conditions->get('table'))
            ->where($this->conditions->get('column'), '=', $value)->get()->fetch();
        if ($record) {
            $this->valid = false;
        }
        return $this->valid;
    }

    public function getErrorMessage()
    {
        return $this->valid === false ? str_replace('{msg}', $this->conditions->get('msg'), $this->errorMessage) : '';
    }
}
