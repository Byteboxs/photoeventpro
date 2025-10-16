<?php

namespace app\core;

abstract class Singleton
{
    private static $instances = [];
    protected $args;

    // Método protegido para permitir diferentes parámetros en el constructor
    protected function __construct(...$args)
    {
        $this->args = $args;
    }

    public static function make(...$args)
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static(...$args);
        }
        return self::$instances[$className];
    }

    // Evita que se pueda clonar la instancia
    final public function __clone()
    {
        throw new \RuntimeException('Cloning is not allowed.');
    }

    // Evita que se pueda deserializar la instancia
    final public function __wakeup()
    {
        throw new \RuntimeException('Unserializing is not allowed.');
    }
}
