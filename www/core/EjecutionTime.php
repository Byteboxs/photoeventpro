<?php

namespace app\core;

class EjecutionTime
{
    private static $start_time;

    public static function init()
    {
        self::$start_time = microtime(true);
    }

    public static function stop()
    {
        if (self::$start_time === null) {
            throw new \RuntimeException('El temporizador no ha sido iniciado.');
        }

        $end_time = microtime(true);
        return $end_time - self::$start_time;
    }

    public static function show()
    {
        $execution_time = self::stop();
        echo "<pre style='color: green'><br>Tiempo de ejecución: " . number_format($execution_time, 6) . " segundos<br></pre>";
    }
}
