<?php
// require_once '../vendor/autoload.php';

// use app\middelwares\EnsureTokenIsValid;
// use app\core\routes\Route;
// use app\core\EjecutionTime;
// use app\core\Path;
// use app\core\Request;
// use app\controllers\UserController;
// use app\controllers\ApiController;
// use app\core\database\Builder;
// use app\core\database\Schema;
// use app\core\Application;


// if (!defined('DB_HOST')) {
//     define('DB_HOST', 'db');
// }

// if (!defined('DB_NAME')) {
//     define('DB_NAME', 'sysmed');
// }

// if (!defined('DB_USER')) {
//     define('DB_USER', 'root');
// }

// if (!defined('DB_PASS')) {
//     define('DB_PASS', 'test');
// }

// if (!defined('DB_PORT')) {
//     define('DB_PORT', '3306');
// }

// if (!defined('DB_CHARSET')) {
//     define('DB_CHARSET', 'utf8mb4');
// }

// if (!defined('DSN')) {
//     define('DSN', "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "; ");
// }

// $config = [
//     'app' => [
//         'config' => [
//             'dsn' => DSN,
//             'user' => DB_USER,
//             'pass' => DB_PASS,
//         ],
//         'pdo_options' => [
//             \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
//             \PDO::ATTR_EMULATE_PREPARES => false,
//             \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
//         ],
//         'databse' => [
//             'prepared' =>
//             Application::RAW
//         ]
//     ],
//     'base_url' => 'http://localhost:8084',
//     'base_path' => Path::getBasePath()
// ];

// EjecutionTime::init();
// $app = new Application(__DIR__, $config);

// // Definir un grupo de rutas para API
// $app->route->group(function ($router) {
//     $router->get('/', [ApiController::class, 'home']);
//     // $router->post('/users', 'ApiController@createUser');
// }, [
//     'prefix' => '/api',
//     'middleware' => [EnsureTokenIsValid::class],
// ]);

// $app->run();
// EjecutionTime::show();
