<?php

namespace app\services\files;

class LocalStorageStrategy implements StorageStrategy
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $this->ensureDirectoryExists();
    }

    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->basePath)) {
            if (!mkdir($this->basePath, 0777, true)) {
                throw new \RuntimeException('No se pudieron crear las carpetas necesarias.');
            }
        } elseif (!is_writable($this->basePath)) {
            throw new \RuntimeException('El directorio de subida no tiene permisos de escritura.');
        }
    }

    public function save(array $file, string $fileName): bool
    {
        return move_uploaded_file($file['tmp_name'][0], $this->getFilePath($fileName));
    }

    public function exists(string $fileName): bool
    {
        return file_exists($this->getFilePath($fileName));
    }

    public function getFilePath(string $fileName): string
    {
        return $this->basePath . '/' . $fileName;
    }
}
