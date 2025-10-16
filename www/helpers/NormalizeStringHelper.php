<?php

namespace app\helpers;

use app\core\Singleton;

class NormalizeStringHelper extends Singleton
{
    public function normalize(string $string): string
    {
        $string = mb_strtoupper($string);
        $string = preg_replace('/[.,\s]+$/', '', $string);
        return $string;
    }
}
