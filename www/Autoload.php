<?php
/**
 * Nombre del archivo.php
 *
 * Descripción corta de lo que hace este archivo.
 *
 * @package    Nombre del Paquete (opcional)
 * @subpackage Subpaquete (opcional)
 * @category   Categoria del archivo (opcional)
 * @license    Licencia MIT
 * @author     Tu Nombre
 * @link       URL del proyecto o documentación relacionada
 * @version    1.0.0
 * @since      Fecha en que se creó el archivo (en formato YYYY-MM-DD)
 */

class Autoload
{
    /**
     * Registers a function to be used as an autoloader.
     *
     * @param string $class The name of the class to be loaded.
     * @throws Exception If the file does not exist.
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            // Convierte el nombre de la clase en un camino de archivo
            $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
            // Si el archivo existe, cárgalo
            if (file_exists($file)) {
                require_once($file);
            }
        });
    }

}

Autoload::register();