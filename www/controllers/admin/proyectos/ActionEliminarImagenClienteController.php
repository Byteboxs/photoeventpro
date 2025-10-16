<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IRunnable;
use app\core\Application;
use app\models\Picture;
use app\services\imageuploader\GDThumbnailGenerator;
use app\services\imageuploader\ImageUploader;
use app\services\ProjectsService;
use ZipArchive;

class ActionEliminarImagenClienteController implements IRunnable
{
    private $response;
    private $imageId;
    private Picture $picture;
    private string $pictureName;
    private string $zipName;
    private string $filePath;
    private string $dirName;
    private string $thumbnailsDirname;
    private $proyectoId;
    private $clienteId;
    private $builder;

    public function __construct()
    {
        $this->response = Application::$app->response;
        $this->picture = new Picture();
        $this->builder = Application::$app->builder;
    }

    // delete file inside a .zip, given the .zip and the file name. Returns a boolean true on success otherwise false.
    private function deleteFileFromZip($zipFile, $fileToDelete)
    {
        $zip = new ZipArchive;
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $zip->deleteName($fileToDelete);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    public function run(...$args)
    {
        // echo '<pre>';
        // var_dump($args['request']->getData());
        // echo '</pre>';
        $this->imageId = $args['request']->getData()['imageId'];
        $this->picture = $this->picture->find($this->imageId);
        $this->filePath = $this->picture->file_path;
        $path_parts = pathinfo($this->filePath);

        $this->dirName = $path_parts['dirname'];
        $this->thumbnailsDirname = $this->dirName . '/thumbnails';
        $this->pictureName = $path_parts['basename'];

        $dirNameParts = explode('/', $this->dirName);
        $this->proyectoId = $dirNameParts[count($dirNameParts) - 2];
        $this->clienteId = $dirNameParts[count($dirNameParts) - 1];

        $this->zipName = "images_{$this->proyectoId}_{$this->clienteId}.zip";

        $data = [
            'imageId' => $this->imageId,
            'picture' => $this->picture,
            'dirName' => $this->dirName,
            'tumbnailsDirname' => $this->thumbnailsDirname,
            'pictureName' => $this->pictureName,
            'zipName' => $this->zipName,
        ];

        $result = ProjectsService::make()->deleteImage($data);
        $this->response->json($result)->send();
    }
}
