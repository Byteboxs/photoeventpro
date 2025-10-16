<?php

namespace app\core\security;

class PasswordGeneratorFactory
{
    public static function create(): PasswordGeneratorInterface
    {
        return new PasswordGenerator();
    }
}
