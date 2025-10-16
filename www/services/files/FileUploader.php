<?php

namespace app\services\files;

class FileUploader implements FileUploaderInterface
{
    private StorageStrategy $storage;
    private ValidationHandler $validator;
    private FileNameGenerator $nameGenerator;

    public function __construct(
        StorageStrategy $storage,
        ValidationHandler $validator,
        FileNameGenerator $nameGenerator
    ) {
        $this->storage = $storage;
        $this->validator = $validator;
        $this->nameGenerator = $nameGenerator;
    }

    public function upload(array $file): Result
    {
        try {
            // Validar el archivo
            $validationResult = $this->validator->handle($file);
            if ($validationResult['status'] === 'error') {
                return Result::error($validationResult['message'], $validationResult);
            }

            // Generar nombre de archivo único
            $fileName = $this->nameGenerator->generate($file);

            // Verificar si el archivo ya existe
            if ($this->storage->exists($fileName)) {
                return Result::error('El archivo ya existe.');
            }

            // Guardar el archivo
            if (!$this->storage->save($file, $fileName)) {
                return Result::error('Error al guardar el archivo.');
            }

            // Devolver resultado exitoso
            return Result::success('Archivo subido con éxito.', [
                'file_path' => $this->storage->getFilePath($fileName),
                'file_name' => $fileName
            ]);
        } catch (\Exception $e) {
            return Result::error('Error en el proceso de carga: ' . $e->getMessage());
        }
    }

    public function uploadMultiple(array $files): Result
    {
        $results = [];
        $success = true;
        $failedUploads = 0;

        // Si no hay archivos, devolver error
        if (empty($files['name']) || count($files['name']) == 0) {
            return Result::error('No se proporcionaron archivos para subir.');
        }

        // Reorganizar el array de archivos para procesarlos individualmente
        $filesCount = count($files['name']);

        for ($i = 0; $i < $filesCount; $i++) {
            $singleFile = [
                'name' => [$files['name'][$i]],
                'type' => [$files['type'][$i]],
                'tmp_name' => [$files['tmp_name'][$i]],
                'error' => [$files['error'][$i]],
                'size' => [$files['size'][$i]]
            ];

            // Validar y subir cada archivo
            $result = $this->upload($singleFile);

            // Guardar el resultado
            $results[] = [
                'original_name' => $files['name'][$i],
                'result' => $result->toArray()
            ];

            // Si algún archivo falla, marcar el resultado general como fallido
            if (!$result->isSuccess()) {
                $success = false;
                $failedUploads++;
            }
        }

        // Construir el mensaje de resultado
        if ($success) {
            $message = 'Todos los archivos fueron subidos con éxito.';
        } else {
            $message = "$failedUploads de $filesCount archivos fallaron al subir.";
        }

        // Devolver resultado completo
        return $success
            ? Result::success($message, ['files' => $results])
            : Result::error($message, ['files' => $results]);
    }
}
