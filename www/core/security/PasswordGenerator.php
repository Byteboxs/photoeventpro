<?php

namespace app\core\security;

class PasswordGenerator implements PasswordGeneratorInterface
{
    private const CHARS_LOWER = 'abcdefghijklmnopqrstuvwxyz';
    private const CHARS_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const CHARS_DIGITS = '0123456789';
    private const CHARS_SPECIAL = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    const STRENGTH_LOW = 1;
    const STRENGTH_MEDIUM = 2;
    const STRENGTH_STRONG = 3;

    public function generate(int $length, int $strength): string
    {
        $charSets = $this->getCharSets($strength);
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $set = $charSets[array_rand($charSets)];
            $password .= $set[array_rand(str_split($set))];
        }

        return str_shuffle($password);
    }

    private function getCharSets(int $strength): array
    {
        $charSets = [self::CHARS_LOWER, self::CHARS_UPPER];

        if ($strength >= 2) {
            $charSets[] = self::CHARS_DIGITS;
        }

        if ($strength >= 3) {
            $charSets[] = self::CHARS_SPECIAL;
        }

        return $charSets;
    }
}
