<?php

namespace app\controllers\admin\proyectos;

use app\controllers\IDrawable;
use app\controllers\template\UISneatTemplateController;
use app\core\views\View;
use app\models\Customer;
use app\models\Project;
use app\models\User;
use app\services\driveimageviewer\client\Client;
use app\services\driveimageviewer\drive\DriveClient;
use app\services\driveimageviewer\drive\DriveFile;
use app\services\driveimageviewer\finder\FolderFinder;
use app\services\driveimageviewer\image\ImageRenderer;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use NumberFormatter;

class UIimagenesClienteController extends UISneatTemplateController implements IDrawable
{
    private $content;
    private $view;
    private $breadcrumb;
    private $proyecto_id;
    private $proyecto;
    private $cliente_id;
    private $cliente;
    private $images = [];
    public function __construct()
    {
        parent::__construct();
    }
    private function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-eventos', 'Eventos')
            ->addLink(APP_DIRECTORY_PATH . '/evento/detalle/' . $args['proyecto']['id'], $args['proyecto']['name'])
            ->addActive($args['cliente']['name']);
    }

    private function initContent(array $args = [])
    {
        $this->content = new View('admin.proyectos.clientes.imagenes', [
            'title' => 'Imagenes de ' . $args['name'],
            'subtitle' => 'Lorem ipsum',
            'actionLink' => '#',
            'actionClass' => 'btn-warning',
            'icon' => 'fas fa-edit',
            'actionText' => 'Editar',
            'images' => $this->images
        ]);
    }

    private function initImages(array $args = [])
    {
        $config = [
            'credentials_path' => PROJECT_ROOT . '/lector-446923-d377c1ffac96.json',
            'application_name' => 'lectorimagenes drive',
        ];
        $driveClient = new DriveClient($config);
        $folderFinder = new FolderFinder($driveClient);
        $imageRenderer = new ImageRenderer($driveClient);
        $client = new Client($args['numero_identificacion']);
        $name = $client->getName();
        $folder = $folderFinder->findFolderByName($name);
        if ($folder) {
            $service = $driveClient->getService();
            $results = $service->files->listFiles([
                'q' => "'" . $folder->getId() . "' in parents and mimeType contains 'image/' and trashed=false",
            ]);
            if (count($results->getFiles()) == 0) {
                // echo "No se encontraron imágenes en la carpeta.";
                return [];
            } else {
                $files = $results->getFiles();

                foreach ($files as $file) {
                    // echo "<h4>Imagen: " . $file->getName() . "</h4>";
                    $driveFile = new DriveFile($file->getId(), $file->getName(), $file->getMimeType());
                    $this->images[] = $imageRenderer->getOptimizedImage($driveFile, 100, 100);
                    // var_dump($driveFile);
                    // $imageRenderer->renderCustomImage($driveFile, 100, 100);
                }
            }
        }
    }
    private function initView($session)
    {
        $this->view = $this->create($session, $this->breadcrumb, $this->content);
    }
    public function draw(...$args)
    {
        $request = $args["request"];
        $session = $request->session();
        $data = $request->getData();
        $this->proyecto_id = $data['proyecto'];
        $this->cliente_id = $data['id'];

        $proyectoModel = new Project();
        $proyecto = $proyectoModel->find($this->proyecto_id);

        $customerModel = new Customer();
        $customer = $customerModel->find($this->cliente_id);
        $user = $customer->getUser();

        $args = [
            'proyecto' => [
                'id' => $proyecto->id,
                'name' => $proyecto->nombre_evento,
            ],
            'cliente' => [
                'id' => $customer->id,
                'name' => $user->nombre_completo,
                'numero_identificacion' => $user->numero_identificacion
            ]
        ];

        $this->initBreadcrumb($args);
        $this->initImages($args['cliente']);
        $this->initContent($args['cliente']);
        $this->initView($session);
        $this->response->view($this->view)->send();
    }
}
