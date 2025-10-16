<?php

namespace app\services\files;

/**
 * Clase utilitaria para reorganizar el array $_FILES, especialmente
 * para el caso de múltiples archivos con el mismo nombre de campo HTML
 */
class FileArrayUtil
{
    /**
     * Reorganiza un array $_FILES cuando se usa input type="file" múltiple
     * o varios inputs con el mismo nombre (ej: name="files[]")
     * 
     * @param array $filesArray El array $_FILES['field_name']
     * @return array Array reorganizado de archivos
     */
    public static function normalizeMultipleFilesArray(array $filesArray): array
    {
        $normalizedFiles = [];

        // Verificar si estamos tratando con múltiples archivos
        if (!is_array($filesArray['name'])) {
            // Es un solo archivo, devolver el array tal cual
            return [$filesArray];
        }

        // Para multiple archivos, reorganizar
        foreach ($filesArray['name'] as $index => $name) {
            // Ignorar entradas vacías
            if (empty($name)) {
                continue;
            }

            $normalizedFiles[] = [
                'name' => [$name],
                'type' => [$filesArray['type'][$index]],
                'tmp_name' => [$filesArray['tmp_name'][$index]],
                'error' => [$filesArray['error'][$index]],
                'size' => [$filesArray['size'][$index]]
            ];
        }

        return $normalizedFiles;
    }

    /**
     * Método alternativo para procesar todo el array $_FILES directamente
     * útil cuando tienes múltiples campos de archivos en un formulario
     */
    public static function normalizeFilesArray(array $filesArray): array
    {
        $normalized = [];

        foreach ($filesArray as $fieldName => $field) {
            if (is_array($field['name'])) {
                // Múltiples archivos para este campo
                foreach (array_keys($field['name']) as $index) {
                    // Solo procesar archivos que fueron realmente subidos
                    if (!empty($field['name'][$index])) {
                        $normalized[$fieldName][] = [
                            'name' => [$field['name'][$index]],
                            'type' => [$field['type'][$index]],
                            'tmp_name' => [$field['tmp_name'][$index]],
                            'error' => [$field['error'][$index]],
                            'size' => [$field['size'][$index]],
                            'field_name' => $fieldName
                        ];
                    }
                }
            } else {
                // Un solo archivo para este campo
                $normalized[$fieldName] = [$field];
            }
        }

        return $normalized;
    }
}
