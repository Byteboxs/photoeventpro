<?php

namespace app\services\files;

abstract class ValidationHandler
{
    protected ?ValidationHandler $nextHandler = null;

    public function setNext(ValidationHandler $handler): ValidationHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(array $file): array
    {
        $result = $this->validate($file);

        if ($result['status'] === 'error' || $this->nextHandler === null) {
            return $result;
        }

        return $this->nextHandler->handle($file);
    }

    abstract protected function validate(array $file): array;

    protected function success(string $message = 'Validación exitosa', array $debug = []): array
    {
        return ['status' => 'success', 'message' => $message, 'debug' => $debug];
    }

    protected function error(string $message, array $debug = []): array
    {
        return ['status' => 'error', 'message' => $message, 'debug' => $debug];
    }
}


// // Se pueden añadir más validadores:
// class FileExtensionValidator extends ValidationHandler
// {
//     // Validación de extensión
// }

// class VirusScanValidator extends ValidationHandler
// {
//     // Validación de virus
// }
