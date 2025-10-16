<?php

namespace app\services\driveimageviewer\finder;

use app\services\driveimageviewer\drive\DriveClient;
use app\services\driveimageviewer\drive\DriveFolder;

class FolderFinder
{

    private DriveClient $driveClient;

    public function __construct(DriveClient $driveClient)
    {
        $this->driveClient = $driveClient;
    }

    public function findFolderByName(string $folderName): ?DriveFolder
    {
        $service = $this->driveClient->getService();
        $query = "mimeType='application/vnd.google-apps.folder' and name='" . $folderName . "' and trashed=false";
        $optParams = ['q' => $query];
        $results = $service->files->listFiles($optParams);

        if (count($results->getFiles()) > 0) {
            $file = $results->getFiles()[0];
            return new DriveFolder($file->getId(), $file->getName());
        } else {
            return null;
        }
    }
}
