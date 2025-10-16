<?php

namespace app\services\files;

interface FileUploaderInterface
{
    /**
     * Sube un archivo y devuelve un objeto Result
     * 
     * @param array $file Array con la información del archivo (generalmente $_FILES['name'])
     * @return Result Objeto con el resultado de la operación
     */
    public function upload(array $file): Result;
    /**
     * Sube múltiples archivos y devuelve un objeto Result
     * 
     * @param array $files Array con la información de los archivos
     * @return Result Objeto con el resultado de la operación
     */
    public function uploadMultiple(array $files): Result;
}
