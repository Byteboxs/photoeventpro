<?php

namespace app\services\driveimageviewer\image;

use app\core\Application;
use app\helpers\ImageResizer;
use app\services\driveimageviewer\drive\DriveClient;
use app\services\driveimageviewer\drive\DriveFile;

// class ImageRenderer
// {

//     private DriveClient $driveClient;

//     public function __construct(DriveClient $driveClient)
//     {
//         $this->driveClient = $driveClient;
//     }

//     public function renderImage(DriveFile $file): void
//     {
//         $service = $this->driveClient->getService();
//         $request = $service->files->get($file->getId(), array('alt' => 'media'));
//         $content = $request->getBody()->getContents();
//         echo "<img src='data:" . $file->getMimeType() . ";base64," . base64_encode($content) . "' width='200' class='img-thumbnail'/>";
//     }
//     public function getImage(DriveFile $file)
//     {
//         $service = $this->driveClient->getService();
//         $request = $service->files->get($file->getId(), array('alt' => 'media'));
//         $content = $request->getBody()->getContents();
//         return "<img class='img-thumbnail rounded' src='data:" . $file->getMimeType() . ";base64," . base64_encode($content) . "' width='200'/>";
//     }

//     public function renderCustomImage(DriveFile $file, int $width, int $height): void
//     {
//         $service = $this->driveClient->getService();
//         $request = $service->files->get($file->getId(), array('alt' => 'media'));
//         $content = $request->getBody()->getContents();

//         try {
//             // Usar ImageResizer
//             $resizer = new ImageResizer('data://' . $file->getMimeType() . ';base64,' . base64_encode($content));

//             //Redimensionar manteniendo la proporcion
//             if ($width === 0 && $height === 0) {
//                 throw new \InvalidArgumentException("Debes especificar un ancho o un alto mayor a cero.");
//             } else if ($width > 0 && $height === 0) {
//                 $resizer->resizeWidth($width);
//             } else if ($width === 0 && $height > 0) {
//                 $resizer->resizeHeight($height);
//             } else {
//                 $resizer->resize($width, $height, function ($constraint) {
//                     $constraint->aspectRatio();
//                     $constraint->upsize();
//                 });
//             }
//             $resizedContent = $resizer->getImageBlob();

//             echo "<img src='data:image/jpeg;base64," . base64_encode($resizedContent) . "' width='{$width}' height='{$height}' class='img-thumbnail'/>";
//         } catch (\InvalidArgumentException $e) {
//             // Manejar la excepción, por ejemplo, mostrando un mensaje de error o usando la imagen original
//             error_log("Error al procesar la imagen: " . $e->getMessage());
//             echo "<img src='data:" . $file->getMimeType() . ";base64," . base64_encode($content) . "' width='{$width}' height='{$height}' alt='Error al procesar la imagen' class='img-thumbnail'>";
//         }
//     }
//     public function getCustomImage(DriveFile $file, int $width, int $height)
//     {
//         $service = $this->driveClient->getService();
//         $request = $service->files->get($file->getId(), array('alt' => 'media'));
//         $content = $request->getBody()->getContents();

//         try {
//             // Usar ImageResizer
//             $resizer = new ImageResizer('data://' . $file->getMimeType() . ';base64,' . base64_encode($content));

//             //Redimensionar manteniendo la proporcion
//             if ($width === 0 && $height === 0) {
//                 throw new \InvalidArgumentException("Debes especificar un ancho o un alto mayor a cero.");
//             } else if ($width > 0 && $height === 0) {
//                 $resizer->resizeWidth($width);
//             } else if ($width === 0 && $height > 0) {
//                 $resizer->resizeHeight($height);
//             } else {
//                 $resizer->resize($width, $height, function ($constraint) {
//                     $constraint->aspectRatio();
//                     $constraint->upsize();
//                 });
//             }
//             $resizedContent = $resizer->getImageBlob();

//             return "<img src='data:image/jpeg;base64," . base64_encode($resizedContent) . "' width='{$width}' height='{$height}' class='img-thumbnail'/>";
//         } catch (\InvalidArgumentException $e) {
//             // Manejar la excepción, por ejemplo, mostrando un mensaje de error o usando la imagen original
//             error_log("Error al procesar la imagen: " . $e->getMessage());
//             return "<img src='data:" . $file->getMimeType() . ";base64," . base64_encode($content) . "' width='{$width}' height='{$height}' alt='Error al procesar la imagen' class='img-thumbnail'>";
//         }
//     }
// }
class ImageRenderer
{

    private DriveClient $driveClient;
    private $logger;

    public function __construct(DriveClient $driveClient)
    {
        $this->logger = Application::$app->logger;
        $this->driveClient = $driveClient;
    }

    public function renderImage(DriveFile $file, int $width = 200, string $class = 'img-thumbnail'): void
    {
        echo $this->getImage($file, $width, $class);
    }

    public function getImage(DriveFile $file, int $width = 200, string $class = 'img-thumbnail')
    {
        $service = $this->driveClient->getService();
        try {
            $thumbnailLink = $service->files->get($file->getId(), ['fields' => 'thumbnailLink'])->getThumbnailLink();
            if ($thumbnailLink) {
                return "<img src='" . $thumbnailLink . "' width='" . $width . "' class='" . $class . "' loading='lazy' alt='" . $file->getName() . "'>";
            }
        } catch (\Exception $e) {
            error_log("Error al obtener la miniatura de Drive: " . $e->getMessage());
        }
        try {
            $request = $service->files->get($file->getId(), array('alt' => 'media'));
            $content = $request->getBody()->getContents();
            return "<img src='data:" . $file->getMimeType() . ";base64," . base64_encode($content) . "' width='" . $width . "' class='" . $class . "' loading='lazy' alt='" . $file->getName() . "'>";
        } catch (\Exception $e) {
            error_log("Error al obtener la imagen de Drive: " . $e->getMessage());
            return "Error al cargar la imagen."; //O un placeholder.
        }
    }

    public function renderCustomImage(DriveFile $file, int $width, int $height, string $class = 'img-thumbnail'): void
    {
        echo $this->getCustomImage($file, $width, $height, $class);
    }

    public function getCustomImage(DriveFile $file, int $width, int $height, string $class = 'img-thumbnail')
    {
        $service = $this->driveClient->getService();
        try {
            $request = $service->files->get($file->getId(), array('alt' => 'media'));
            $content = $request->getBody()->getContents();
            $resizer = new ImageResizer('data://' . $file->getMimeType() . ';base64,' . base64_encode($content));

            if ($width === 0 && $height === 0) {
                throw new \InvalidArgumentException("Debes especificar un ancho o un alto mayor a cero.");
            } else if ($width > 0 && $height === 0) {
                $resizer->resizeWidth($width);
            } else if ($width === 0 && $height > 0) {
                $resizer->resizeHeight($height);
            } else {
                $resizer->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            $resizedContent = $resizer->getImageBlob();
            return "<img src='data:image/jpeg;base64," . base64_encode($resizedContent) . "' width='{$width}' height='{$height}' class='" . $class . "' loading='lazy' alt='" . $file->getName() . "'>";
        } catch (\InvalidArgumentException $e) {
            error_log("Error al procesar la imagen: " . $e->getMessage());
            return $this->getImage($file, $width, $class); // Intenta obtener la imagen original como respaldo
        } catch (\Exception $e) {
            error_log("Error al obtener la imagen de Drive: " . $e->getMessage());
            return "Error al cargar la imagen.";
        }
    }

    public function renderOptimizedImage(DriveFile $file, int $width, int $height, string $class = 'img-thumbnail'): void
    {
        echo $this->getOptimizedImage($file, $width, $height, $class);
    }

    public function getOptimizedImage(DriveFile $file, int $width, int $height, string $class = 'img-thumbnail')
    {
        $service = $this->driveClient->getService();
        try {
            // 1. Intentar obtener la miniatura de Drive
            $thumbnailLink = $service->files->get($file->getId(), ['fields' => 'thumbnailLink'])->getThumbnailLink();
            $this->logger->log("Error: ", "Thumbnail Link original: " . $thumbnailLink);
            if ($thumbnailLink && $width <= 200 && $height <= 200) { //Si existe la miniatura y las dimensiones son menores a 200px se muestra la miniatura
                $cleanThumbnailLink = stripslashes($thumbnailLink);
                // $this->logger->log("Error: ", "Thumbnail Link limpio: " . htmlspecialchars($cleanThumbnailLink));
                $headers = @get_headers($cleanThumbnailLink);
                if ($headers && strpos($headers[0], '200 OK') !== false) {
                    // Obtener las dimensiones de la miniatura para determinar la orientación
                    list($thumbWidth, $thumbHeight) = @getimagesize($cleanThumbnailLink);
                    return "<img src='" . htmlspecialchars($cleanThumbnailLink) . "' " .
                        "width='" . $thumbWidth . "' " .
                        "height='" . $thumbHeight . "' " .
                        "class='rounded mx-auto d-block m-1' " .
                        // "loading='lazy' " .
                        "alt='" . htmlspecialchars($file->getName()) . "'>";
                } else {
                    $this->logger->log("Error: ", "Error al acceder a la miniatura de Drive, codigo de respuesta: " . ($headers[0] ?? "Sin codigo de respuesta"));
                }
            }

            // 2. Si no hay miniatura o se necesitan dimensiones mayores, usar ImageResizer
            $request = $service->files->get($file->getId(), array('alt' => 'media'));
            $content = $request->getBody()->getContents();
            $resizer = new ImageResizer('data://' . $file->getMimeType() . ';base64,' . base64_encode($content));

            if ($width === 0 && $height === 0) {
                throw new \InvalidArgumentException("Debes especificar un ancho o un alto mayor a cero.");
            } else if ($width > 0 && $height === 0) {
                $resizer->resizeWidth($width);
            } else if ($width === 0 && $height > 0) {
                $resizer->resizeHeight($height);
            } else {
                $resizer->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $resizedContent = $resizer->getImageBlob();
            return "<img src='data:image/jpeg;base64," . base64_encode($resizedContent) . "' width='{$width}' height='{$height}' class='" . $class . "' loading='lazy' alt='" . $file->getName() . "'>";
        } catch (\InvalidArgumentException $e) {
            $this->logger->log("Error", "Error al procesar la imagen: " . $e->getMessage());
            return $this->getImage($file, $width, $class); // Intenta obtener la imagen original como respaldo
        } catch (\Exception $e) {
            $this->logger->log("Error", "Error al obtener la imagen de Drive: " . $e->getMessage());
            return "Error al cargar la imagen.";
        }
    }
}
