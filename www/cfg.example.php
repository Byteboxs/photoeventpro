<?php

use app\core\Application;
use app\core\Path;

require_once 'vendor/autoload.php';

if (!defined('DB_HOST')) {
    define('DB_HOST', 'mysql');
    // define('DB_HOST', 'localhost');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'photoeventpro');
    // define('DB_NAME', 'u205316560_photoeventpro');
}

if (!defined('DB_USER')) {
    define('DB_USER', '[USUARIO_DB]');
    // define('DB_USER', 'u205316560_photoeventpro');
}

if (!defined('DB_PASS')) {
    define('DB_PASS', '[PASSWORD_DB]');
    // define('DB_PASS', 'chpOozY0&');
}

if (!defined('DB_PORT')) {
    define('DB_PORT', '3306');
}

if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

if (!defined('DSN')) {
    define('DSN', "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "; ");
}

if (!defined('SESSION_NAME')) {
    define('SESSION_NAME', 'photoApp');
}
return [
    'mysql' => [
        'prepared' => Application::RAW,
        'dsn' => DSN,
        'host' => DB_HOST,
        'database' => DB_NAME,
        'username' => DB_USER,
        'password' => DB_PASS,
        'user' => DB_USER,
        'pass' => DB_PASS,
        'pdo_options' => [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]
    ],
    'pgsql' => [],
    'base_url' => Path::getBaseUrl(),
    'base_path' => Path::getBasePath(),
    'upload_dir' => 'uploads',
    'debug' => true,
    'prepared' => Application::RAW,
    'connection' => 'mysql',
];
