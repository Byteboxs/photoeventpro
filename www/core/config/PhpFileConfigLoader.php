<?php

namespace app\core\config;

use app\core\exceptions\ConfigurationException;

class PhpFileConfigLoader implements ConfigLoaderInterface
{
    private string $configPath;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    public function load(): array
    {
        if (!file_exists($this->configPath)) {
            throw new ConfigurationException("Configuration file not found: {$this->configPath}");
        }

        $config = require $this->configPath;

        if (!is_array($config)) {
            throw new ConfigurationException("Invalid configuration format");
        }

        return $config;
    }
}
