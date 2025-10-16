<?php

namespace core\trash;

class Blueprint
{
    private $columns = [];
    private $commands = [];

    private $current = '';

    public $engine = 'InnoDB';
    public $charset = 'utf8mb4';
    public $collation = 'utf8mb4_unicode_ci';

    private $columnsMap;

    public function __construct()
    {
        $this->columnsMap = new \core\collections\Map();
    }

    private function add(Column $column): Column
    {
        $this->columns[$column->name] = $column;
        return $column;
    }

    public function tinyInteger($name): Column
    {
        return $this->add(new Column())->tinyInteger($name);
    }

    public function smallInteger($name): Column
    {
        return $this->add(new Column())->smallInteger($name);
    }

    public function integer($name): Column
    {
        return $this->add(new Column())->integer($name);
    }

    public function mediumInteger($name): Column
    {
        return $this->add(new Column())->mediumInteger($name);
    }

    public function bigInteger($name): Column
    {
        return $this->add(new Column())->bigInteger($name);
    }

    public function binary($name): Column
    {
        return $this->add(new Column())->blob($name);
    }

    public function boolean($name): Column
    {
        return $this->add(new Column())->boolean($name);
    }

    public function decimal($name, $precision, $scale = 0): Column
    {
        return $this->add(new Column())->decimal($name, $precision, $scale);
    }

    public function double($name, $precision, $scale = 0): Column
    {
        return $this->add(new Column())->double($name, $precision, $scale);
    }

    public function float($name, $precision = null, $scale = null): Column
    {
        return $this->add(new Column())->float($name, $precision, $scale);
    }

    public function numeric($name, $precision = null, $scale = null): Column
    {
        return $this->add(new Column())->numeric($name, $precision, $scale);
    }

    public function char($name, $length = 255): Column
    {
        return $this->add(new Column())->char($name, $length);
    }

    public function varchar($name, $length = 255): Column
    {
        return $this->add(new Column())->varchar($name, $length);
    }

    public function tinyText($name): Column
    {
        return $this->add(new Column())->tinyText($name);
    }

    public function text($name): Column
    {
        return $this->add(new Column())->text($name);
    }

    public function mediumText($name): Column
    {
        return $this->add(new Column())->mediumText($name);
    }

    public function longText($name): Column
    {
        return $this->add(new Column())->longText($name);
    }

    public function date($name): Column
    {
        return $this->add(new Column())->date($name);
    }

    public function time($name): Column
    {
        return $this->add(new Column())->time($name);
    }

    public function dateTime($name, $precision = 0): Column
    {
        return $this->add(new Column())->dateTime($name, $precision);
    }

    public function timestamp($name, $precision = 0): Column
    {
        return $this->add(new Column())->timestamp($name, $precision);
    }

    public function dateTimeTz($name, $precision = 0, $timezone = 'UTC'): Column
    {
        return $this->add(new Column())->timestampTz($name, $precision, $timezone);
    }

    public function timestamps($precision = 0): void
    {
        $this->timestamp("created_at", $precision)
            ->default("CURRENT_TIMESTAMP")
            ->notNull();
        $this->timestamp("updated_at", $precision)
            ->default("CURRENT_TIMESTAMP")
            ->onUpdate()
            ->null();
    }

    public function blob($name): Column
    {
        return $this->add(new Column())->blob($name);
    }

    public function foreignId($name)
    {
        $this->current = $name;
        $this->add(new Column())->bigInteger($name)->unsigned();
        return $this;
    }

    public function foreign($column)
    {
        $fk = new \core\database\Foreign();
        $this->addCommand('foreignId', $fk);
        return $fk->foreign($column);
    }

    public function addCommand($command, $parameters)
    {
        $this->commands[] = compact('command', 'parameters');
    }

    protected function processForeignId($parameters)
    {
        $command = $parameters->getCommand();

        $constraint = sprintf(
            "CONSTRAINT `fk_%s_foreign` FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`)",
            $command['foreign'],
            $command['foreign'],
            $command['on'],
            $command['references']
        );

        return $constraint;
    }

    protected function processCommand($command)
    {
        if ($command['command'] === 'foreignId') {
            return $this->processForeignId($command['parameters']);
        }
        return '';
    }

    public function constrained($table = '')
    {
        if ($table == '') {
            [$part1, $part2] = explode('_', $this->current);
            $this->foreign($this->current)
                ->references($part2)
                ->on($part1 . "s");
        } else {
            $this->foreign($this->current)
                ->references("id")
                ->on($table);
        }
    }

    public function getColumn($name)
    {
        return $this->columns[$name];
    }

    public function getSql(): string
    {
        $sql = '';
        // echo '<pre>';
        // var_dump($this->columns);
        // echo '</pre>';

        $columnSqls = array_map(function (Column $column) {
            return $column->getSql();
        }, $this->columns);

        $sql .= implode(', ', $columnSqls);

        $commandSqls = array_map([$this, 'processCommand'], $this->commands);

        if (count($commandSqls) > 0) {
            $sql .= ', ';
            $sql .= implode(', ', $commandSqls);
        }
        return $sql;
    }
}
