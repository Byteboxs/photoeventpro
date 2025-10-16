<?php

namespace app\core\database\builder;

use app\core\Application;
use app\core\database\connection\ConnectionInterface;
use app\core\database\connection\ConnectionPool;
use app\models\UsuariosRepository;

class RepositoryFactory
{
    private static ?RepositoryFactory $instance = null;
    private $connectionName;
    private $connection;
    private $queryBuilder;
    private function __construct(string $connectionName)
    {
        // $config = Application::$app->config;
        $pool = new ConnectionPool();

        $this->connectionName = $connectionName;
        $this->connection = $pool->getConnection($this->connectionName);
        if ($this->connectionName === 'mysql') {
            $this->queryBuilder = new MySQLQueryBuilder();
        }
    }
    public static function getInstance(string $connectionName): RepositoryFactory
    {
        if (self::$instance === null) {
            self::$instance = new self($connectionName);
        }
        return self::$instance;
    }

    public function createRepository(string $repositoryType): RepositoryInterface
    {
        return match ($repositoryType) {
            default => throw new \InvalidArgumentException("Repositorio no encontrado: $repositoryType"),
        };
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
