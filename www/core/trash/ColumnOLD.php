<?php

namespace core\trash;

class ColumnOLD
{
    public string $name = '';
    private $modifiers = [];

    public function __construct()
    {
    }

    private function column(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    private function addModifier(string $modifier): self
    {
        $this->modifiers[] = $modifier;
        return $this;
    }

    private function removeModifier(string $modifier): self
    {
        $this->modifiers = array_filter($this->modifiers, function ($m) use ($modifier) {
            return $m !== $modifier;
        });
        return $this;
    }

    function integer($name): self
    {
        return $this->column($name)->addModifier('INT');
    }

    function smallInteger($name): self
    {
        return $this->column($name)->addModifier('SMALLINT');
    }

    function tinyinteger($name): self
    {
        return $this->column($name)->addModifier('TINYINT');
    }

    function mediumInteger($name): self
    {
        return $this->column($name)->addModifier('MEDIUMINT');
    }

    function bigInteger($name): self
    {
        return $this->column($name)->addModifier('BIGINT');
    }

    private function real($type, $name, $precision = 10, $scale = 0)
    {
        $modifier = sprintf('%s(%d,%d)', $type, $precision, $scale);
        return $this->column($name)->addModifier($modifier);
    }

    function decimal($name, $precision = 10, $scale = 0): self
    {
        return $this->real('DECIMAL', $name, $precision, $scale);
    }

    public function float($name, $precision = 10, $scale = 0): self
    {
        return $this->real('FLOAT', $name, $precision, $scale);
    }

    public function double($name, $precision = 10, $scale = 0): self
    {
        return $this->real('DOUBLE', $name, $precision, $scale);
    }

    public function numeric($name, $precision = 10, $scale = 0): self
    {
        return $this->real('NUMERIC', $name, $precision, $scale);
    }

    public function boolean($name): self
    {
        return $this->column($name)->addModifier('BOOLEAN');
    }

    public function char($name, $length = 255): self
    {
        return $this->column($name)->addModifier(sprintf('CHAR(%d)', $length));
    }

    public function varchar($name, $length = 255): self
    {
        return $this->column($name)->addModifier(sprintf('VARCHAR(%d)', $length));
    }

    public function text($name): self
    {
        return $this->column($name)->addModifier('TEXT');
    }

    public function tinyText($name): self
    {
        return $this->column($name)->addModifier('TINYTEXT');
    }

    public function mediumText($name): self
    {
        return $this->column($name)->addModifier('MEDIUMTEXT');
    }

    public function longText($name): self
    {
        return $this->column($name)->addModifier('LONGTEXT');
    }

    public function date($name): self
    {
        return $this->column($name)->addModifier('DATE');
    }

    public function time($name): self
    {
        return $this->column($name)->addModifier('TIME');
    }

    public function dateTime($name, $precision = 0): self
    {
        $modifier = ($precision == 0) ? 'DATETIME' : sprintf('DATETIME(%d)', $precision);
        return $this->column($name)->addModifier($modifier);
    }

    public function timestamp($name, $precision = 0): self
    {
        $modifier = ($precision == 0) ? 'TIMESTAMP' : sprintf('TIMESTAMP(%d)', $precision);
        return $this->column($name)->addModifier($modifier);
    }

    public function timestampTz($name, $precision = 0, $timezone = 'UTC'): self
    {
        $timestamp = $this->timestamp($name, $precision);

        if ($timezone !== 'UTC') {
            $timestamp->addModifier(sprintf('WITH TIME ZONE "%s"', $timezone));
        }

        return $timestamp;
    }

    public function blob($name): self
    {
        return $this->column($name)->addModifier('BLOB');
    }

    private function toggleModifier(string $modifier, bool $status): self
    {
        if ($status) {
            return $this->addModifier($modifier);
        } else {
            return $this->removeModifier($modifier);
        }
    }

    function unsigned(bool $unsigned = true): self
    {
        return $this->toggleModifier('UNSIGNED', $unsigned);
    }

    function unique(bool $unique = true): self
    {
        return $this->toggleModifier('UNIQUE', $unique);
    }

    function primary(bool $primary = true): self
    {
        return $this->toggleModifier('PRIMARY KEY', $primary);
    }

    function autoIncrement(bool $autoIncrement = true): self
    {
        return $this->toggleModifier('AUTO_INCREMENT', $autoIncrement);
    }

    function bigIntIncrements($name): self
    {
        return $this->bigInteger($name)->unsigned()->primary()->autoIncrement();
    }

    function id($name = "id"): self
    {
        return $this->bigIntIncrements($name);
    }

    function default($value): self
    {
        return $this->addModifier(sprintf("DEFAULT %s", $value));
    }

    public function null(): self
    {
        return $this->addModifier('NULL');
    }

    public function notNull(): self
    {
        return $this->addModifier('NOT NULL');
    }

    public function onUpdate()
    {
        return $this->addModifier('ON UPDATE CURRENT_TIMESTAMP');
    }

    public function getSql(): string
    {
        return $this->name . ' ' . implode(' ', $this->modifiers);
    }
}
