<?php

namespace app\core\database\schema;

use app\core\Application;
use app\core\database\blueprint\Blueprint;
use app\core\database\builder\Builder;
use app\core\collections\Map;

class Schema
{
    private Blueprint $blueprint;
    private Builder $builder;
    private $db;
    private $schema = 'sysmed';
    public function __construct($database)
    {
        $this->db = $database;
        $this->blueprint = Application::$app->blueprint;
        $this->builder = Application::$app->builder;
    }

    public function create(string $name, \Closure $callback)
    {
        // $blueprint = new Blueprint();
        $callback($this->blueprint);
        $sql = sprintf('CREATE TABLE %s (%s);', $name, $this->blueprint);
        $this->db->run($sql);
    }

    public function rename(string $from, string $to)
    {
        $sql = sprintf('RENAME TABLE %s TO %s;', $from, $to);
        $this->db->run($sql);
    }

    public function drop(string $name)
    {
        $sql = sprintf('DROP TABLE %s;', $name);
        $this->db->run($sql);
    }

    public function after(string $name, \Closure $callback)
    {
        // $blueprint = new Blueprint();
        $callback($this->blueprint);
        $columns = $this->blueprint->getColumns()->toArray(); // Array con los objetos
        foreach ($columns as $column) {
            $column->add();
        }
        $sql = sprintf('ALTER TABLE %s %s;', $name, $this->blueprint);
        $this->db->run($sql);
    }

    public function table(string $name, \Closure $callback)
    {
        // $blueprint = new Blueprint();
        $callback($this->blueprint);
        $sql = sprintf('ALTER TABLE %s %s;', $name, $this->blueprint);
        echo $sql;
        $this->db->run($sql);
    }

    public function dropIfExists(string $name)
    {
        $sql = sprintf('DROP TABLE IF EXISTS %s;', $name);
        $this->db->run($sql);
    }

    public function addColumnsAfter(string $name, \Closure $callback)
    {
        // $blueprint = new Blueprint();
        $callback($this->blueprint);
        $sql = sprintf('ALTER TABLE %s (%s);', $name, $this->blueprint);
        $this->db->run($sql);
    }

    public function hasTable(string $name): bool
    {
        $stmt = $this->builder->table('information_schema.tables')
            ->where('table_schema', '=', $this->schema)
            ->where('table_name', '=', $name)->get();
        // $stmt = $this->db->run($builder->query(), $builder->params());
        if ($stmt->fetchAll()) {
            return true;
        } else {
            return false;
        }
    }

    public function hasColumn($table, $column): bool
    {
        $stmt = $this->builder->select('column_name')
            ->from('information_schema.columns')
            ->where('table_name', '=', $table)
            ->where('column_name', '=', $column)->get();

        // $stmt = $this->db->run($builder->query(), $builder->params());
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return !empty($result);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/migrations');

        $toApplyMigrations = array_diff($files, $appliedMigrations);

        $newMigrations = [];
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            require Application::$ROOT_DIR . '/migrations/' . $migration;
            $classNmae = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $classNmae();
            $instance->up();
            $newMigrations[] = ['migration' => $migration];
        }
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            echo "No hay migraciones nuevas" . PHP_EOL;
        }
    }

    public function deleteMigration(string $migration)
    {
        // $builder = new Builder($this->db);
        // $config = new Map();
        // $config->add("prepared", Application::PREPARED_NAMED);
        // $builder->config($config);
        $this->builder->table('migrations')->where('migration', '=', $migration)->delete();
    }

    public function downMigration(string $migration)
    {
        require Application::$ROOT_DIR . '/migrations/' . $migration;
        $classNmae = pathinfo($migration, PATHINFO_FILENAME);
        $instance = new $classNmae();
        $instance->down();
        $this->deleteMigration($migration);
    }

    public function hasMigration(string $migration)
    {
        // $builder = new Builder($this->db);
        // $config = new Map();
        // $config->add("prepared", Application::PREPARED_NAMED);
        // $builder->config($config);
        $Stmt = $this->builder->table('migrations')->where('migration', '=', $migration)->get();
        // $stmt = $this->db->run($builder->query(), $builder->params());
        if ($Stmt->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAppliedMigrations(): array
    {
        // $builder = new \core\database\Builder($this->db);
        // $config = new \core\collections\Map();
        // $config->add("prepared", Application::PREPARED_NAMED);
        // $builder->config($config);
        $builder = Application::$app->builder;
        return $builder->select('migration')
            ->from('migrations')
            ->orderBy('id', 'DESC')->get()->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        // $builder = new \core\database\Builder($this->db);
        // $config = new \core\collections\Map();
        // $config->add("prepared", Application::PREPARED_NAMED);
        // $builder->config($config);
        $builder = Application::$app->builder;
        $builder->table('migrations');
        foreach ($migrations as $migration) {
            $builder->insert($migration);
        }
    }

    public function createMigrationsTable()
    {
        if (!$this->hasTable('migrations')) {
            $this->create('migrations', function (Blueprint $blueprint) {
                $blueprint->int('id')->autoIncrement()->primary();
                $blueprint->string('migration');
                $blueprint->timestamp('created_at')->default('CURRENT_TIMESTAMP');
            });
        }
    }
}
