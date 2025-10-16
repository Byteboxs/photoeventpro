<?php

namespace app\services\files;

class FileTypeValidator extends ValidationHandler
{
    private array $allowedTypes;

    public function __construct(array $allowedTypes)
    {
        $this->allowedTypes = $allowedTypes;
    }

    protected function validate(array $file): array
    {
        $fileType = mime_content_type($file['tmp_name'][0]);

        if (!in_array($fileType, $this->allowedTypes)) {
            return $this->error('Tipo de archivo no permitido: ' . $fileType);
        }

        return $this->success();
    }
}
