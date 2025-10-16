<?php

namespace app\services\files;

class Result
{
    private bool $success;
    private string $message;
    private array $data;

    private function __construct(bool $success, string $message, array $data = [])
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }

    public static function success(string $message = 'Operación exitosa', array $data = []): self
    {
        return new self(true, $message, $data);
    }

    public static function error(string $message, array $data = []): self
    {
        return new self(false, $message, $data);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->success ? 'success' : 'error',
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
