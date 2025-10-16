<?php

namespace app\services\files;

interface StorageStrategy
{
    public function save(array $file, string $fileName): bool;
    public function exists(string $fileName): bool;
    public function getFilePath(string $fileName): string;
}

// // Se pueden implementar otras estrategias como:
// class S3StorageStrategy implements StorageStrategy
// {
//     // Implementación para Amazon S3
// }

// class FTPStorageStrategy implements StorageStrategy
// {
//     // Implementación para FTP
// }
