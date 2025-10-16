<?php

namespace app\core\security;

interface PasswordGeneratorInterface
{
    public function generate(int $length, int $strength): string;
}
