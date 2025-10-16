<?php

namespace app\core\database\connection;

class PostgreSQLConnection implements ConnectionInterface
{
    private ?\PDO $connection = null;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect(): void
    {
        if (!$this->isConnected()) {
            $dsn = "pgsql:host={$this->config['host']};dbname={$this->config['database']}";
            $this->connection = new \PDO($dsn, $this->config['username'], $this->config['password']);
        }
    }

    public function disconnect(): void
    {
        $this->connection = null;
    }

    public function isConnected(): bool
    {
        return $this->connection !== null;
    }

    public function getConnection(): ?\PDO
    {
        return $this->connection;
    }
}
