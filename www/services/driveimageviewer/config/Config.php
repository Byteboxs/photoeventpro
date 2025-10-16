<?php

namespace app\services\driveimageviewer\config;

class Config
{
    private $config;

    public function __construct(string $configPath)
    {
        if (!file_exists($configPath)) {
            throw new \Exception("Archivo de configuracion de la cuenta de Google no encontrado: " . $configPath);
        }
        $this->config = parse_ini_file($configPath);
    }

    public function get(string $section, string $key): string
    {
        if (!isset($this->config[$section][$key])) {
            throw new \Exception("Key " . $key . " no encontrada en seccion " . $section . " del archivo de configuracion");
        }
        return $this->config[$section][$key];
    }
}
