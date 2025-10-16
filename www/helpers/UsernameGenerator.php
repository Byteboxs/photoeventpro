<?php

namespace app\helpers;

class UsernameGenerator
{
    private const MAX_LENGTH = 15;

    public static function generate(string $nombres, string $apellidos): string
    {
        $nombresParts = explode(' ', strtolower($nombres));
        $apellidosParts = explode(' ', strtolower($apellidos));
        $username = '';

        foreach ($nombresParts as $part) {
            $username .= $part[0];
        }

        if (count($apellidosParts) === 1) {
            $username .= $apellidosParts[0];
        } else if (count($apellidosParts) === 2) {
            $username .= $apellidosParts[0] . $apellidosParts[1][0];
        }

        return preg_replace('/[^a-z0-9]/i', '', substr($username, 0, self::MAX_LENGTH));
    }
}
