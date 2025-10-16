<?php

namespace app\services\imageuploader;

use Exception;

class GDThumbnailGenerator implements ThumbnailGenerator
{
    public function generate(string $sourcePath, string $destPath, $newWidth): void
    {
        list($width, $height, $type) = getimagesize($sourcePath);
        // $newWidth = 250;
        $newHeight = (int)(($newWidth / $width) * $height);

        $thumb = imagecreatetruecolor($newWidth, $newHeight);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new Exception("Tipo de imagen no soportado para miniatura.");
        }

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        if (!imagejpeg($thumb, $destPath, 90)) {
            throw new Exception("Error al guardar la miniatura.");
        }

        imagedestroy($thumb);
        imagedestroy($source);
    }
}
