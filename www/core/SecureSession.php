<?php

namespace app\core;

class SecureSession
{
    const SESSION_HASH = 'sha512';
    private $sessionName;
    private $secure;

    private static $instances = [];

    protected function __construct($sessionName, $secure)
    {
        $this->sessionName = $sessionName;
        $this->secure = $secure;
    }

    public static function make($sessionName, $secure = true)
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static($sessionName, $secure);
        }
        return self::$instances[$className];
    }

    public function start()
    {
        // Comprobación de la versión de PHP
        if (session_status() == PHP_SESSION_NONE) {
            // Configurar opciones de sesión para mayor seguridad
            session_cache_limiter('nocache');
            session_set_cookie_params(0, '/', '', $this->secure, true);

            // Asegura que la cookie de sesión no pueda accederse por JavaScript.
            ini_set('session.cookie_httponly', 1);

            if (in_array(self::SESSION_HASH, hash_algos())) {
                // Algoritmo hash para usar con sessionid (sha512 es una opción segura).
                ini_set('session.hash_function', self::SESSION_HASH);
            }

            ini_set('session.hash_bits_per_character', 5);

            // Fuerza a la sesión para que solo use cookies, no variables URL.
            ini_set('session.use_only_cookies', 1);

            // Establecer el nombre de la sesión
            session_name($this->sessionName);

            // Iniciar la sesión
            session_start();
        }
        return $this;
    }
    public function destroy()
    {
        // Limpiar las variables de sesión
        $_SESSION = array();

        // Obtener los parámetros de la cookie de sesión
        $params = session_get_cookie_params();

        // Destruir la cookie de sesión
        setcookie($this->sessionName, '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        // setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

        // Finalmente, destruir la sesión
        return session_destroy();
    }
}
