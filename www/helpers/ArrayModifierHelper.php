<?php

namespace app\helpers;

use app\models\ui\table\ControlDataStrategy;
use Closure;
use InvalidArgumentException;
use ReflectionException;
use ReflectionMethod;

class ArrayModifierHelper
{
    /**
     * Añade una nueva columna a un array de datos, utilizando una estrategia de control de datos.
     *
     * @param string $columnName Nombre de la columna a añadir.
     * @param mixed $strategy Objeto o clase que implementa la estrategia de control de datos.
     * @param array &$array Referencia al array de datos al que se añadirá la columna.
     * @param array $args Parámetros adicionales que se pasarán a la estrategia de control de datos.
     * @param string $method Nombre del método de la estrategia de control de datos que se utilizará para añadir la columna.
     * @param string|null $after Nombre de la columna después de la cual se insertará la nueva columna. Si no se especifica, se añadirá al final.
     *
     * @throws InvalidArgumentException Si el método especificado en la estrategia no existe.
     */
    // public static function addColumn($columnName, $strategy, array &$array, array $args = [], $method = 'add', string $after = null)
    // {

    //     if (!method_exists($strategy, $method)) {
    //         throw new InvalidArgumentException(sprintf('El método "%s" no existe en la clase de estrategia.', $method));
    //     }

    //     foreach ($array as &$item) {
    //         if ($after !== null) {
    //             $afterKey = $after;
    //             if (array_key_exists($afterKey, $item)) {
    //                 $primeraParte = array_slice($item, 0, array_search($afterKey, array_keys($item)) + 1, true);
    //                 $segundaParte = array_slice($item, array_search($afterKey, array_keys($item)) + 1, null, true);

    //                 $newValue = $strategy->{$method}($item, $args);

    //                 $item = array_merge($primeraParte, [$columnName => $newValue], $segundaParte);
    //             } else {
    //                 // Si la clave 'after' NO existe, se añade al final.
    //                 $item[$columnName] = $strategy->{$method}($item, $args);
    //             }
    //         } else {

    //             // Si no se especifica 'after', también se añade al final (comportamiento original).
    //             $item[$columnName] = $strategy->{$method}($item, $args);
    //         }
    //     }
    //     unset($item);
    // }
    public static function addColumn($columnName, $strategy, array &$array, array $args = [], $method = 'add', ?string $after = null)
    {
        try {
            $reflectionMethod = new ReflectionMethod($strategy, $method);
            if (!$reflectionMethod->isPublic()) {
                $reflectionMethod->setAccessible(true); // Permite el acceso a métodos no públicos
            }
        } catch (ReflectionException $e) {
            throw new InvalidArgumentException(sprintf('El método "%s" no existe en la clase de estrategia.', $method));
        }

        foreach ($array as &$item) {
            $newValue = $reflectionMethod->invokeArgs($strategy, [$item, $args]);

            if ($after !== null) {
                $afterKey = $after;
                if (array_key_exists($afterKey, $item)) {
                    $primeraParte = array_slice($item, 0, array_search($afterKey, array_keys($item)) + 1, true);
                    $segundaParte = array_slice($item, array_search($afterKey, array_keys($item)) + 1, null, true);

                    $item = array_merge($primeraParte, [$columnName => $newValue], $segundaParte);
                } else {
                    // Si la clave 'after' NO existe, se añade al final.
                    $item[$columnName] = $newValue;
                }
            } else {
                // Si no se especifica 'after', también se añade al final (comportamiento original).
                $item[$columnName] = $newValue;
            }
        }
        unset($item);
    }

    // public static function modifyColumn(string $columnName, $strategy, array &$array, array $args = [], string $method = 'modify'): void
    // {
    //     // Verificamos si el método existe en la clase de estrategia
    //     if (!method_exists($strategy, $method)) {
    //         throw new InvalidArgumentException(sprintf('El método "%s" no existe en la clase de estrategia.', $method));
    //     }

    //     if (isset($args['rawArray']) && count($args['rawArray']) == count($array)) {
    //         $rawData = $args['rawArray'];
    //         for ($i = 0; $i < count($rawData); $i++) {
    //             $item = $array[$i];
    //             $args['rawItem'] = $rawData[$i];
    //             $array[$i][$columnName] = $strategy->{$method}($item, $args);
    //         }
    //         unset($args['rawArray']); // Eliminamos la referencia residual
    //         unset($item); // Eliminamos la referencia residual
    //     } else {
    //         foreach ($array as &$item) {
    //             // Llamamos al método dinámico solo si existe
    //             $item[$columnName] = $strategy->{$method}($item, $args);
    //         }
    //         unset($item); // Eliminamos la referencia residual
    //     }
    // }

    public static function modifyColumn(string $columnName, $strategy, array &$array, array $args = [], string $method = 'modify'): void
    {
        try {
            $reflectionMethod = new ReflectionMethod($strategy, $method);
            if (!$reflectionMethod->isPublic()) {
                $reflectionMethod->setAccessible(true); // Permite el acceso a métodos no públicos
            }
        } catch (ReflectionException $e) {
            throw new InvalidArgumentException(sprintf('El método "%s" no existe en la clase de estrategia.', $method));
        }

        if (isset($args['rawArray']) && count($args['rawArray']) == count($array)) {
            $rawData = $args['rawArray'];
            for ($i = 0; $i < count($rawData); $i++) {
                $item = $array[$i];
                $args['rawItem'] = $rawData[$i];
                $array[$i][$columnName] = $reflectionMethod->invokeArgs($strategy, [$item, $args]);
            }
            unset($args['rawArray']); // Eliminamos la referencia residual
            unset($item); // Eliminamos la referencia residual
        } else {
            foreach ($array as &$item) {
                // Llamamos al método dinámico usando Reflection
                $item[$columnName] = $reflectionMethod->invokeArgs($strategy, [$item, $args]);
            }
            unset($item); // Eliminamos la referencia residual
        }
    }

    public static function removeColumn(string $columnName, array &$array): void
    {
        foreach ($array as &$item) {
            unset($item[$columnName]);
        }
    }
}
