<?php

/**
 * Nombre del Archivo: RequestHandler.php
 * Descripción: Manejador de solicitudes HTTP para la aplicación XYZ.
 *
 * PHP 8.2
 *
 * @category Librería
 * @package  core
 * @author   Andrés Otálora <aaotalora@poligran.edu.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  1.0.0
 * @link     
 * @since    Archivo disponible desde la versión 1.0.0
 * @created  2023-11-11
 */

namespace app\core;

class RequestHandler
{
    public static function get($key, $default = null)
    {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
    }

    public static function has($key)
    {
        return isset($_REQUEST[$key]);
    }

    public static function getInt($key, $default = 0)
    {
        return isset($_REQUEST[$key]) ? intval($_REQUEST[$key]) : $default;
    }

    public static function getString($key, $default = '')
    {
        return isset($_REQUEST[$key]) ? strval($_REQUEST[$key]) : $default;
    }
}
