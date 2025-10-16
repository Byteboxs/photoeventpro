<?php

namespace app\services\imageuploader;

use Exception;
use ZipArchive;

class ImageUploader
{
    private string $uploadDir;
    private ThumbnailGenerator $thumbnailGenerator;

    public function __construct(string $uploadDir, ThumbnailGenerator $thumbnailGenerator)
    {
        $this->uploadDir = $uploadDir;
        $this->thumbnailGenerator = $thumbnailGenerator;
    }

    public function upload(array $data): array
    {
        $proyectoId = $data['proyecto_id'] ?? null;
        $clienteId = $data['cliente_id'] ?? null;
        $images = $data['images'] ?? [];
        $result = ['status' => 'success', 'details' => []];
        $uploadedFiles = []; // Array to store successfully uploaded file paths

        if (!$proyectoId || !$clienteId) {
            return ['status' => 'error', 'message' => 'Faltan parámetros proyecto_id o cliente_id'];
        }

        $uploadPath = $this->uploadDir;
        $thumbnailPath = $uploadPath . 'thumbnails/';

        if (!$this->createDirectory($uploadPath) || !$this->createDirectory($thumbnailPath)) {
            return ['status' => 'error', 'message' => 'No se pudieron crear las carpetas necesarias.'];
        }

        foreach ($images['name'] as $index => $name) {
            if (empty($name)) continue;

            $filePath = $uploadPath . $name;
            $tmpName = $images['tmp_name'][$index];
            $type = $images['type'][$index];
            $error = $images['error'][$index];
            $size = $images['size'][$index];

            if (file_exists($filePath)) {
                $result['details'][] = ["file" => $name, "status" => "error", "message" => "Archivo duplicado."];
                continue;
            }

            if ($error !== 0) {
                $result['details'][] = ["file" => $name, "status" => "error", "message" => "Error al cargar el archivo (código {$error})."];
                continue;
            }

            if (!$this->validateImage($type, $size)) {
                $result['details'][] = ["file" => $name, "status" => "error", "message" => "Tipo o tamaño de archivo no permitido."];
                continue;
            }

            if (move_uploaded_file($tmpName, $filePath)) {
                try {
                    $thumbnailNewWidth = 250;
                    $this->thumbnailGenerator->generate($filePath, $thumbnailPath . $name, $thumbnailNewWidth);
                    $result['details'][] = ["file" => $name, "status" => "success"];
                    $uploadedFiles[] = $filePath; // Store the path of successfully uploaded file
                } catch (Exception $e) {
                    $result['details'][] = ["file" => $name, "status" => "error", "message" => "Error al crear miniatura: " . $e->getMessage()];
                }
            } else {
                $result['details'][] = ["file" => $name, "status" => "error", "message" => "No se pudo mover el archivo."];
            }
        }

        // Create ZIP archive of uploaded images
        if (!empty($uploadedFiles)) {
            $zipFilename = $uploadPath . "images_{$proyectoId}_{$clienteId}.zip";
            if (!$this->createZipArchive($uploadedFiles, $zipFilename)) {
                $result['details'][] = ["zip_status" => "error", "message" => "Error al crear archivo ZIP."];
                $result['status'] = 'warning'; // Change status to warning, as images were uploaded but zip failed
            } else {
                $result['details'][] = ["zip_status" => "success", "message" => "Archivo ZIP creado."];
            }
        } else {
            $result['details'][] = ["zip_status" => "info", "message" => "No se cre&oacute; archivo ZIP porque no se subieron im&aacute;genes v&aacute;lidas."];
        }


        return $result;
    }

    private function validateImage(string $type, int $size): bool
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        return in_array($type, $allowedTypes) && $size < 5000000;
    }

    private function createDirectory(string $path): bool
    {
        return is_dir($path) || mkdir($path, 0777, true);
    }

    private function createZipArchive(array $files, string $destination): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($destination, ZipArchive::CREATE) === true) {

            foreach ($files as $file) {
                if (file_exists($file)) {
                    if (!$zip->locateName(basename($file))) {
                        $zip->addFile($file, basename($file));
                    }
                }
            }
            $zip->close();
            return file_exists($destination);
        } else {
            return false;
        }
    }
}
