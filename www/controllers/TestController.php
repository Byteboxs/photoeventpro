<?php

namespace app\controllers;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\http\Request;
use app\core\http\Response;
use app\core\SecureSession;
use app\core\security\PasswordGenerator;
use app\core\security\PasswordGeneratorFactory;
use app\core\security\PasswordHashUtility;
use app\models\Order_detail;
use app\models\Project;
use app\models\Project_salespeople;
use app\models\User;
use app\services\AuthService;
use app\services\driveimageviewer\client\Client;
use app\services\driveimageviewer\drive\DriveClient;
use app\services\driveimageviewer\drive\DriveFile;
use app\services\driveimageviewer\finder\FolderFinder;
use app\services\driveimageviewer\image\ImageRenderer;

class TestController
{
    private $response;
    public function __construct()
    {
        $this->response = Application::$app->response;
    }

    public function test(Request $request)
    {
        // try {
        //     echo '<h3>Test</h3><pre>';
        //     $salida = Project_salespeople::getVendedoresDisponiblesByEvento(1);
        //     var_dump($salida);
        //     echo '</pre>';
        // } catch (\Exception $e) {
        //     echo $e->getMessage();
        // }
        $identificationNumber = '52997318';
        $hash = PasswordHashUtility::create()->hashPassword($identificationNumber);
        var_dump($hash);
        $verify = PasswordHashUtility::create()->verifyPassword($identificationNumber, $hash);
        echo '<br>Verificacion: ';
        echo $verify ? 'yes' : 'no';
    }

    public function testSession(Request $request)
    {
        $secureSession = SecureSession::make('bsaApp')->start();
        $session = $request->session();
        $session->put('userId', '1');
        $session->put('userRole', 'Administrator');
        $todos = $session->all();
        $secureSession->destroy();
        $todosBorrados = $session->all();
    }
    public function testSecurity(Request $request)
    {
        $generator = PasswordGeneratorFactory::create();
        $password = $generator->generate(14, PasswordGenerator::STRENGTH_STRONG);
        echo "Contraseña generada: $password";

        $hasPassword = PasswordHashUtility::create();
        $hash = $hasPassword->hashPassword($password);
        $verify = $hasPassword->verifyPassword($password, $hash);
        echo '<br>Hash: ';
        echo $hash;
        echo '<br>Verificacion: ';

        echo $verify ? 'yes' : 'no';
    }

    public function generarModelos(Request $request)
    {
        // Genera el modelo dado el esquema
        // $db = Application::$app->db;
        // $outputPath = PROJECT_ROOT . '/models/';
        // $generator = new SchemaToModelGenerator($db, $outputPath);
        // $generator->generate();
    }

    public function testUsers(Request $request)
    {
        $authService = new AuthService(new User());
        $email = 'administrador@example.com';
        $password = '123456';
        $result = $authService->authenticate($email, $password);
        if ($result) {
            $authService->authorize();

            $session = $request->session();
            var_dump($session->get('userId'));
            var_dump(strtolower($session->get('userRole')));

            // $this->response->status(Response::HTTP_OK);
            // $this->response->content("Usuario autenticado");
            // $this->response->send();
        } else {
            $this->response->status(Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->response->content("No se pudo autenticar el usuario");
            $this->response->send();
        }
    }

    public function testConexionGoogleDrive(Request $request)
    {
        echo '<h3>Test de conexion a google drive </h3>';
        $config = [
            'credentials_path' => PROJECT_ROOT . '/lector-446923-d377c1ffac96.json',
            'application_name' => 'lectorimagenes drive',
        ];
        $driveClient = new DriveClient($config);
        $folderFinder = new FolderFinder($driveClient);
        $imageRenderer = new ImageRenderer($driveClient);
        $client = new Client('80012098');
        $name = $client->getName();
        $folder = $folderFinder->findFolderByName($name);
        if ($folder) {
            $service = $driveClient->getService();
            $results = $service->files->listFiles([
                'q' => "'" . $folder->getId() . "' in parents and mimeType contains 'image/' and trashed=false",
            ]);

            if (count($results->getFiles()) == 0) {
                echo "No se encontraron imágenes en la carpeta.";
            } else {
                $files = $results->getFiles();
                $imageFiles = [];
                foreach ($files as $file) {
                    // echo "<h4>Imagen: " . $file->getName() . "</h4>";
                    $driveFile = new DriveFile($file->getId(), $file->getName(), $file->getMimeType());
                    $imageFiles[] = $imageRenderer->getCustomImage($driveFile, 100, 100);
                    // var_dump($driveFile);
                    // $imageRenderer->renderCustomImage($driveFile, 100, 100);
                }
                foreach ($imageFiles as $image) {
                    echo $image;
                }
            }
        } else {
            echo "No se encontró la carpeta con el nombre " . $client->getName() . ".";
        }
    }
}
