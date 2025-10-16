<?php

namespace app\core;

class Str
{

    /**
     * Check if a value matches a pattern.
     *
     * @param string $pattern The pattern to match.
     * @param string $value The value to check.
     * @return bool Whether the value matches the pattern.
     */
    public static function is(string $pattern, string $value): bool
    {
        // Escape special characters in the pattern
        $escapedPattern = preg_quote($pattern, '/');

        // Replace * with .* in the pattern
        $finalPattern = str_replace('\*', '.*', $escapedPattern);

        // Use preg_match to check for a match
        return preg_match('/' . $finalPattern . '/', $value) === 1;
    }
}
