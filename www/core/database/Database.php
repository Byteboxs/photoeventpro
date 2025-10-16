<?php

namespace app\core\database;

use app\core\Application;

class Database extends \PDO
{
    private static $instances = [];

    // protected function __construct($dsn, $username = NULL, $password = NULL, $options = [])
    protected function __construct(string $connectionName)
    {
        $config = Application::$app->config->get($connectionName);
        $dsn = $config['dsn'];
        $username = $config['user'];
        $password = $config['pass'];
        $pdoOptions = $config['pdo_options'];
        $default_options = [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_replace($default_options, $pdoOptions);
        parent::__construct($dsn, $username, $password, $options);
    }
    public function run($sql, $args = NULL)
    {
        if (!$args) {
            return $this->query($sql);
        }
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    public static function connection(string $connectionName)
    {

        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static($connectionName);
        }
        // var_dump(self::$instances);
        return self::$instances[$class];
    }
}
