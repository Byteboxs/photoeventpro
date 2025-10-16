<?php

namespace app\core;

use app\core\database\builder\Builder;
use app\core\database\schema\Schema;
use app\core\database\blueprint\Blueprint;
use app\core\database\builder\MySQLQueryBuilder;
use app\core\database\connection\ConnectionInterface;
use app\core\database\connection\ConnectionPool;
use app\core\http\Request;
use app\core\routes\Route;
use app\core\http\Response;
use app\core\log\JsonLogFormatter;
use app\core\log\Logger;
use app\core\log\LogWriterFactory;

class Application
{
    public static string $ROOT_DIR;
    public static string $BASE_PATH;
    public static string $BASE_URL;

    public static string $SERVER_FOLDER = '';

    public ConnectionInterface $db;
    public $queryBuilder;
    public $defaultConnection;
    public Schema $schema;
    public ?Builder $builder = null;
    public Blueprint $blueprint;
    public Route $route;
    public Response $response;
    public Request $request;
    const PREPARED_POSITIONAL = 1;
    const PREPARED_NAMED = 2;
    const RAW = 3;
    public Kernel $kernel;

    public $config;

    public Logger $logger;

    public static Application $app;
    public function __construct($rootPath, $config)
    {
        $this->createLogger();
        $this->config = $config;
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        self::$BASE_URL = $config->get('base_url');
        self::$BASE_PATH = $config->get('base_path');
        $pool = new ConnectionPool();
        try {
            $this->db = $pool->getConnection($config->get('connection'));
            $this->queryBuilder = new MySQLQueryBuilder();
            $this->builder = new Builder($this->db, $this->queryBuilder);
            $this->blueprint = new Blueprint();
            $this->schema = new Schema($this->db);
        } catch (\Exception $e) {
            $this->logger->log('error', $e->getMessage());
        }
        $this->route = new Route();
        $this->response = new Response();
        $this->request = new Request();
        $this->kernel = new Kernel();
        $this->defaultConnection = $config->get('defaultConnection');
    }

    private function createLogger()
    {
        $writer = LogWriterFactory::createFileLogWriter('../log/app.log');
        $formatter = new JsonLogFormatter();
        $this->logger = new Logger($writer, $formatter);
    }

    public function run()
    {
        $this->route->dispatch($this->request);
    }
}
