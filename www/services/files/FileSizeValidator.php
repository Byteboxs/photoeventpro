<?php

namespace app\services\files;

class FileSizeValidator extends ValidationHandler
{
    private int $maxSize;

    public function __construct(int $maxSize)
    {
        $this->maxSize = $maxSize;
    }

    protected function validate(array $file): array
    {
        if ($file['size'][0] > $this->maxSize) {
            return $this->error(
                'El archivo excede el tamaño permitido de ' . $this->maxSize . ' bytes. Tamaño archivo: ' . $file['size'][0]
            );
        }

        return $this->success();
    }
}
