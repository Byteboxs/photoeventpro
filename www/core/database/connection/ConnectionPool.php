<?php

namespace app\core\database\connection;

use app\core\Application;

class ConnectionPool
{
    private array $connections = [];

    public function getConnection(string $type): ConnectionInterface
    {
        $config = Application::$app->config;
        if (!isset($this->connections[$type])) {
            $this->connections[$type] = $this->createConnection($type, $config->get($type));
        }

        return $this->connections[$type];
    }

    private function createConnection(string $type, array $config): ConnectionInterface
    {
        return match ($type) {
            'mysql' => new MySQLConnection($config),
            'pgsql' => new PostgreSQLConnection($config),
                // 'mongodb' => new MongoDBConnection($config),
            default => throw new \InvalidArgumentException("Unsupported connection type: $type"),
        };
    }

    public function releaseConnection(string $type): void
    {
        if (isset($this->connections[$type])) {
            $this->connections[$type]->disconnect();
            unset($this->connections[$type]);
        }
    }

    public function clearPool(): void
    {
        foreach ($this->connections as $connection) {
            $connection->disconnect();
        }

        $this->connections = [];
    }
}
