<?php

namespace app\services\files;

// Clase para generar nombres de archivo
class FileNameGenerator
{
    private string $prefix;

    public function __construct(string $prefix = '')
    {
        $this->prefix = $prefix;
    }

    public function generate(array $file): string
    {
        $originalName = $file['name'][0];
        // $uniqueId = uniqid($this->prefix);
        $uniqueId = $this->prefix;

        return $uniqueId . '' . $originalName;
    }
}
