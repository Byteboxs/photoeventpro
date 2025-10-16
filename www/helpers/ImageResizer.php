<?php

namespace app\helpers; // Ajusta el namespace a tu estructura

use Intervention\Image\ImageManagerStatic as Image;

class ImageResizer
{
    private $imagePath;
    private $image;

    public function __construct(string $imagePath)
    {
        $this->imagePath = $imagePath;
        try {
            $this->image = Image::make($this->imagePath);
        } catch (\Intervention\Image\Exception\NotReadableException $e) {
            throw new \InvalidArgumentException("La imagen no es válida o no se puede leer: " . $e->getMessage());
        }
    }

    public function resize(int $width = null, int $height = null, $constraint = null): self
    {
        if ($width === null && $height === null) {
            throw new \InvalidArgumentException("Debes especificar al menos un ancho o un alto.");
        }
        if ($constraint === null) {
            $this->image->resize($width, $height);
        } else {
            $this->image->resize($width, $height, $constraint);
        }
        return $this;
    }
    public function resizeWidth(int $width): self
    {
        $this->image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        return $this;
    }

    public function resizeHeight(int $height): self
    {
        $this->image->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        return $this;
    }

    public function quality(int $quality): self
    {
        $this->image->encode(null, $quality);
        return $this;
    }

    public function save(string $outputPath = null): bool
    {
        if ($outputPath === null) {
            $outputPath = $this->imagePath; // Sobreescribe la imagen original si no se especifica una ruta de salida
        }
        try {
            $this->image->save($outputPath);
        } catch (\Exception $e) {
            error_log("Error al guardar la imagen: " . $e->getMessage());
            return false;
        }

        return true;
    }
    public function getImageBlob()
    {
        return $this->image->encode()->getEncoded();
    }
}
