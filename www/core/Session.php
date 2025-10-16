<?php

namespace app\core;

class Session
{
    // Instancia única de la clase
    private static $instance = null;
    private $sessionData = [];
    private function __construct()
    {
        $this->sessionData = $_SESSION;
    }
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    // Método para obtener un elemento de la sesión con un valor predeterminado opcional
    public function get($key, $default = null)
    {
        $this->sessionData = $_SESSION;
        // Verificar si la clave existe en la sesión
        if (isset($this->sessionData[$key])) {
            // Si la clave existe, devolver el valor correspondiente
            return $this->sessionData[$key];
        } else {
            // Si la clave no existe, verificar si $default es un cierre (closure)
            if ($default instanceof \Closure) {
                // Si $default es un cierre, ejecutarlo y devolver su resultado
                return $default();
            } else {
                // Si no es un cierre, devolver el valor predeterminado
                return $default;
            }
        }
    }

    // Método para recuperar todos los datos de la sesión
    public function all()
    {
        $this->sessionData = $_SESSION;
        // Devolver una copia de los datos de la sesión para evitar modificar $_SESSION directamente
        return $this->sessionData;
    }

    // Método para verificar si un elemento está presente en la sesión y no es null
    public function has($key)
    {
        $this->sessionData = $_SESSION;
        return isset($this->sessionData[$key]) && $this->sessionData[$key] !== null;
    }

    // Método para verificar si un elemento está presente en la sesión, incluso si su valor es null
    public function exists($key)
    {
        $this->sessionData = $_SESSION;
        return array_key_exists($key, $this->sessionData);
    }

    // Método para verificar si un elemento está ausente en la sesión
    public function missing($key)
    {
        $this->sessionData = $_SESSION;
        return !array_key_exists($key, $this->sessionData);
    }

    // Método para almacenar un elemento en la sesión
    public function put($key, $value)
    {
        // Almacenar el elemento en la sesión
        $_SESSION[$key] = $value;
        // Actualizar la copia en $sessionData
        $this->sessionData[$key] = $value;
    }

    // Método para insertar un nuevo valor en un valor de sesión que es un array
    public function push($key, $value)
    {
        $this->sessionData = $_SESSION;
        // Verificar si la clave ya existe y es un array
        if (isset($this->sessionData[$key]) && is_array($this->sessionData[$key])) {
            // Insertar el nuevo valor en el array existente
            $this->sessionData[$key][] = $value;
            // Actualizar $_SESSION
            $_SESSION[$key] = $this->sessionData[$key];
        } else {
            // Si la clave no existe o no es un array, crear un nuevo array con el valor
            $this->sessionData[$key] = [$value];
            // Actualizar $_SESSION
            $_SESSION[$key] = $this->sessionData[$key];
        }
    }

    // Método para obtener un elemento de la sesión y eliminarlo
    public function pull($key, $default = null)
    {
        $this->sessionData = $_SESSION;
        // Verificar si la clave está presente en la sesión
        if (array_key_exists($key, $this->sessionData)) {
            // Obtener el valor de la clave
            $value = $this->sessionData[$key];
            // Eliminar la clave de la sesión
            unset($_SESSION[$key]);
            // Eliminar la clave de la copia local
            unset($this->sessionData[$key]);
            // Devolver el valor de la clave
            return $value;
        } else {
            // Si la clave no está presente, devolver el valor predeterminado
            return $default;
        }
    }

    // Método para incrementar un valor de sesión
    public function increment($key, $incrementBy = 1)
    {
        $this->sessionData = $_SESSION;
        // Verificar si la clave está presente en la sesión y es un número entero
        if (isset($this->sessionData[$key]) && is_numeric($this->sessionData[$key])) {
            // Incrementar el valor de la clave en la sesión y en la copia local
            $_SESSION[$key] += $incrementBy;
            $this->sessionData[$key] += $incrementBy;
        } else {
            // Si la clave no está presente o no es un número, establecer el valor inicial
            $_SESSION[$key] = $incrementBy;
            $this->sessionData[$key] = $incrementBy;
        }
    }

    // Método para disminuir un valor de sesión
    public function decrement($key, $decrementBy = 1)
    {
        $this->sessionData = $_SESSION;
        // Verificar si la clave está presente en la sesión y es un número entero
        if (isset($this->sessionData[$key]) && is_numeric($this->sessionData[$key])) {
            // Disminuir el valor de la clave en la sesión y en la copia local
            $_SESSION[$key] -= $decrementBy;
            $this->sessionData[$key] -= $decrementBy;
        } else {
            // Si la clave no está presente o no es un número, establecer el valor inicial negativo
            $_SESSION[$key] = -$decrementBy;
            $this->sessionData[$key] = -$decrementBy;
        }
    }

    private function forgetSingle($key)
    {
        // Verificar si la clave está presente en la sesión
        if ($this->exists($key)) {
            // Eliminar la clave de la sesión y de la copia local
            unset($_SESSION[$key]);
            unset($this->sessionData[$key]);
        }
    }

    // Método para eliminar datos de la sesión
    public function forget($keys)
    {
        // Verificar si $keys es un array (para eliminar múltiples claves) o una sola clave
        if (is_array($keys)) {
            // Iterar sobre las claves y eliminarlas de la sesión y de la copia local
            foreach ($keys as $key) {
                $this->forgetSingle($key);
            }
        } else {
            // Si $keys es una sola clave, eliminarla de la sesión y de la copia local
            $this->forgetSingle($keys);
        }
    }

    // Método para regenerar el ID de sesión
    public function regenerate()
    {
        // Regenerar el ID de sesión
        session_regenerate_id(true);
        // Actualizar la copia local con el nuevo ID de sesión
        $this->sessionData = $_SESSION;
    }

    // Método para regenerar el ID de sesión y eliminar todos los datos de la sesión
    public function invalidate()
    {
        // Regenerar el ID de sesión y eliminar los datos de la sesión
        session_regenerate_id(true);
        $_SESSION = [];
        // Actualizar la copia local con la nueva sesión vacía
        $this->sessionData = $_SESSION;
    }
}
