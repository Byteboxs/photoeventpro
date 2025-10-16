<?php

namespace app\core\security;

class PasswordHashUtility
{
    private $pepper = "J]`PTXsqdZy(g;F@Q.2DVU:,Av~K"; // Cambia esto por una cadena aleatoria y segura
    private static $instances = [];
    protected function __construct() {}

    public static function create()
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }
        return self::$instances[$className];
    }

    public function hashPassword($password)
    {
        // Combina la contraseña con el pepper
        $peppered = hash_hmac("sha256", $password, $this->pepper);

        // Crea un hash seguro
        $hashed = password_hash($peppered, PASSWORD_ARGON2ID);

        return $hashed;
    }

    public function verifyPassword($password, $hash)
    {
        // Combina la contraseña proporcionada con el pepper
        $peppered = hash_hmac("sha256", $password, $this->pepper);

        // Verifica la contraseña
        return password_verify($peppered, $hash);
    }
}
