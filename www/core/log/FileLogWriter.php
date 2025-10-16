<?php

namespace app\core\log;

class FileLogWriter implements LogWriterInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $directory = dirname($filePath);

        // Verifica si el directorio existe, y si no, lo crea
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new \RuntimeException(sprintf('No se pudo crear el directorio: %s', $directory));
            }
        }
    }

    public function write(string $formattedLog): void
    {
        file_put_contents($this->filePath, $formattedLog . PHP_EOL, FILE_APPEND);
    }
}
