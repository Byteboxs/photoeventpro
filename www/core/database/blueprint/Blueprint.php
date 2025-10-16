<?php

namespace app\core\database\blueprint;

use app\core\collections\ArrayList;
use app\core\collections\Map;
use app\core\database\blueprint\Column;

class Blueprint
{
    public $engine = 'InnoDB';
    public $charset = 'utf8mb4';
    public $collation = 'utf8mb4_unicode_ci';

    private $columns;
    private $constraints;

    // String data types
    public const CHAR = 'CHAR';
    public const VARCHAR = 'VARCHAR';
    public const BINARY = 'BINARY';
    public const VARBINARY = 'VARBINARY';
    public const TINYTEXT = 'TINYTEXT';
    public const TEXT = 'TEXT';
    public const MEDIUMTEXT = 'MEDIUMTEXT';
    public const LONGTEXT = 'LONGTEXT';
    public const TINYBLOB = 'TINYBLOB';
    public const BLOB = 'BLOB';
    public const MEDIUMBLOB = 'MEDIUMBLOB';
    public const LONGBLOB = 'LONGBLOB';

    //Numeric data types
    public const BIT = 'BIT';
    public const TINYINT = 'TINYINT';
    public const BOOL = 'BOOL';
    public const BOOLEAN = 'BOOLEAN';
    public const SMALLINT = 'SMALLINT';
    public const MEDIUMINT = 'MEDIUMINT';
    public const INT = 'INT';
    public const INTEGER = 'INTEGER';
    public const BIGINT = 'BIGINT';
    public const FLOAT = 'FLOAT';
    public const DOUBLE = 'DOUBLE';
    public const DECIMAL = 'DECIMAL';

    //Date and time data types
    public const DATE = 'DATE';
    public const DATETIME = 'DATETIME';
    public const TIMESTAMP = 'TIMESTAMP';
    public const TIME = 'TIME';
    public const YEAR = 'YEAR';

    public function __construct()
    {
        $this->columns = new Map();
        $this->constraints = new ArrayList();
    }

    public function column(Column $column)
    {
        if (!$this->columns->contains($column->getName())) {
            $this->columns->add($column->getName(), $column);
        }
    }

    public function getColumns(): Map
    {
        return $this->columns;
    }

    public function constraint(Constraint $constraint)
    {
        $this->constraints->add($constraint);
    }

    private function validateSizeRange(int $size, int $min, int $max): void
    {
        if ($size < $min || $size > $max) {
            throw new \InvalidArgumentException('Invalid size range. Size must be between 0 and 65535.');
        }
    }

    /**
     * Create a CHAR column.
     *
     * @param string $name The name of the column.
     * @param int $size The size of the column.
     * @return Column The created column object.
     */
    public function char(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 255);
        $column = (new Column())
            ->dataType(self::CHAR)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function varchar(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 65535);
        $column = (new Column())
            ->dataType(self::VARCHAR)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function binary(string $name = '', int $size = 1): Column
    {
        $column = (new Column())
            ->dataType(self::BINARY)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function varbinary(string $name = '', int $size = 1): Column
    {
        $column = (new Column())
            ->dataType(self::VARBINARY)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function tinytext(string $name = ''): Column
    {
        $column = (new Column())
            ->dataType(self::TINYTEXT)->name($name);
        $this->column($column);
        return $column;
    }

    public function text(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 65535);
        $column = (new Column())
            ->dataType(self::TEXT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function mediumtext(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 16777215);
        $column = (new Column())
            ->dataType(self::MEDIUMTEXT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function longtext(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 4294967295);
        $column = (new Column())
            ->dataType(self::LONGTEXT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function string(string $name = '', int $size = 512): Column
    {
        return $this->varchar($name, $size);
    }

    public function tinyblob(string $name = ''): Column
    {
        $column = (new Column())
            ->dataType(self::TINYBLOB)->name($name);
        $this->column($column);
        return $column;
    }

    public function blob(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 65535);
        $column = (new Column())
            ->dataType(self::BLOB)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function mediumblob(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 16777215);
        $column = (new Column())
            ->dataType(self::MEDIUMBLOB)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function longblob(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 0, 4294967295);
        $column = (new Column())
            ->dataType(self::LONGBLOB)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function bit(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, 1, 64);
        $column = (new Column())
            ->dataType(self::BIT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function tinyint(string $name = '', int $size = 1): Column
    {
        $this->validateSizeRange($size, -128, 127);
        $column = (new Column())
            ->dataType(self::TINYINT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function bool(string $name = ''): Column
    {
        $column = (new Column())
            ->dataType(self::BOOL)->name($name);
        $this->column($column);
        return $column;
    }

    public function boolean(string $name = ''): Column
    {
        return $this->bool($name);
    }

    public function smallint(string $name = '', int $size = 10): Column
    {
        $this->validateSizeRange($size, 1, 255);
        $column = (new Column())
            ->dataType(self::SMALLINT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function mediumint(string $name = '', int $size = 10): Column
    {
        $this->validateSizeRange($size, 1, 255);
        $column = (new Column())
            ->dataType(self::MEDIUMINT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function int(string $name = '', int $size = null): Column
    {
        $column = (new Column());
        if ($size === null) {
            $column->dataType(self::INT)->name($name);
        } else {
            $column->dataType(self::INT)->name($name)->params($size);
        }
        $this->column($column);
        return $column;
    }

    public function integer(string $name = '', int $size = 10): Column
    {
        return $this->int($name, $size);
    }

    public function biginteger(string $name = '', int $size = 10): Column
    {
        $this->validateSizeRange($size, 1, 255);
        $column = (new Column())
            ->dataType(self::BIGINT)->name($name)->params($size);
        $this->column($column);
        return $column;
    }

    public function float(string $name = ''): Column
    {
        $column = (new Column())
            ->dataType(self::FLOAT)->name($name);
        $this->column($column);
        return $column;
    }

    public function double(string $name = ''): Column
    {
        $column = (new Column())
            ->dataType(self::DOUBLE)->name($name);
        $this->column($column);
        return $column;
    }

    public function decimal(string $name = '', int $size = 65, int $scale = 10): Column
    {
        $column = (new Column())
            ->dataType(self::DECIMAL)->name($name)->params($size, $scale);
        $this->column($column);
        return $column;
    }

    public function date(string $name = ''): Column
    {
        $column = (new Column())
            ->dataType(self::DATE)->name($name);
        $this->column($column);
        return $column;
    }

    public function datetime(string $name = '', int $fps = 0): Column
    {
        $this->validateSizeRange($fps, 0, 6);
        $column = (new Column())
            ->dataType(self::DATETIME)->name($name)->params($fps);
        $this->column($column);
        return $column;
    }

    public function timestamp(string $name = '', int $fps = 0): Column
    {
        $this->validateSizeRange($fps, 0, 6);
        $column = (new Column())
            ->dataType(self::TIMESTAMP)->name($name)->params($fps);
        $this->column($column);
        return $column;
    }

    public function time(string $name = '', int $fps = 0): Column
    {
        $this->validateSizeRange($fps, 0, 6);
        $column = (new Column())
            ->dataType(self::TIME)->name($name)->params($fps);
        $this->column($column);
        return $column;
    }

    public function year(string $name = ''): Column
    {
        $column = (new Column())
            ->dataType(self::YEAR)->name($name);
        $this->column($column);
        return $column;
    }

    public function renameColumn(string $oldName, string $newName): void
    {
        $column = (new Column())
            ->name($oldName)->rename($newName);
        $this->column($column);
    }

    public function dropColumn(string $name): void
    {
        $column = (new Column())
            ->name($name)->drop();
        $this->column($column);
    }

    public function foreign(string $column = ''): Foreign
    {
        $constraint = new Constraint();
        $foreign = $constraint->foreign($column);
        $this->constraint($constraint);
        return $foreign;
    }

    public function primary($column = ''): Primary
    {
        $constraint = new Constraint();
        $primary = $constraint->primary($column);
        $this->constraint($constraint);
        return $primary;
    }

    public function toSql(): string
    {
        $sql = '';
        $sql .= implode(', ', $this->columns->toArray());
        $sql .= implode(', ', $this->constraints->toArray());
        return $sql;
    }

    public function __toString()
    {
        return $this->toSql();
    }
}
