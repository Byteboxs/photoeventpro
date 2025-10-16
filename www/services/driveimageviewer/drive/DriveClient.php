<?php

namespace app\services\driveimageviewer\drive;

use app\services\driveimageviewer\config\Config;
use Google\Client;
use Google\Service\Drive;


// class DriveClient
// {
//     private $service;

//     public function __construct(Config $config)
//     {
//         $client = new Client();
//         $client->setApplicationName($config->get('drive', 'application_name'));
//         $client->setAuthConfig($config->get('drive', 'credentials_path'));
//         $client->setScopes([Drive::DRIVE_READONLY]);

//         $this->service = new Drive($client);
//     }

//     public function getService(): Drive
//     {
//         return $this->service;
//     }
// }
class DriveClient
{
    private $service;

    public function __construct(array $config)
    {
        $client = new Client();
        $client->setApplicationName($config['application_name']); // Accede a la configuración desde el array
        $client->setAuthConfig($config['credentials_path']); // Accede a la configuración desde el array
        $client->setScopes([Drive::DRIVE_READONLY]);

        $this->service = new Drive($client);
    }

    public function getService(): Drive
    {
        return $this->service;
    }
}
