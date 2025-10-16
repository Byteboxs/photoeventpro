<?php

namespace app\core\database\connection;

class MySQLConnection extends \PDO implements ConnectionInterface
{
    // private ?\PDO $connection = null;
    private array $config;
    private bool $isConnected = false;

    public function __construct(array $config)
    {
        $this->config = $config;
        $default_options = [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_replace($default_options, $this->config['pdo_options']);
        $dsn = "mysql:host={$this->config['host']};dbname={$this->config['database']}";
        parent::__construct($dsn, $this->config['username'], $this->config['password'], $options);
        $this->isConnected = true;
    }

    public function connect(): void
    {
        if (!$this->isConnected) {
            $this->__construct($this->config);
        }
    }

    public function disconnect(): void
    {
        $this->isConnected = false;
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    public function getConnection(): ?\PDO
    {
        return $this->isConnected ? $this : null;
    }

    public function run($sql, $args = NULL): ?\PDOStatement
    {
        // if (!$args) {
        //     return $this->query($sql);
        // }
        // $stmt = $this->prepare($sql);
        // $stmt->execute($args);
        // return $stmt;
        $stmt = $this->prepare($sql);

        if ($stmt === null) {
            throw new \PDOException("Error al preparar la sentencia: $sql");
        }

        $result = $stmt->execute($args ?? []);

        if (!$result) {
            throw new \PDOException("Error al ejecutar la sentencia: " . $stmt->errorInfo()[2]);
        }

        return $stmt;
    }
}
