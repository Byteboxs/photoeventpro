<?php

namespace app\core\config;

use app\core\exceptions\ConfigurationException;

class Config
{
    private static ?Config $instance = null;
    private array $config;

    private function __construct(ConfigLoaderInterface $loader)
    {
        $this->config = $loader->load();
    }

    public static function create(ConfigLoaderInterface $loader = null): Config
    {
        if (self::$instance === null) {
            if ($loader === null) {
                throw new ConfigurationException("Config loader is required for initial instantiation");
            }
            self::$instance = new self($loader);
        }
        return self::$instance;
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($this->config[$key]);
    }
}
