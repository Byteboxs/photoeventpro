<?php

namespace app\controllers\admin\proyectos;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\Customer;
use app\models\Order_detail;
use app\models\Project;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\HtmlFactory;
use app\services\ui\html\HtmlServices;
use app\services\ui\html\Icon;
use app\services\ui\paginator\Paginator;
use app\services\UICustomersEventTableService;
use app\services\UIOrderDetailsTableService;

class UIEventoClienteDetalleController  extends SneatAdvancedController implements PageBuilder
{
    private $proyecto;
    private $idEvento;
    private $cliente;
    private $informacionBasicaCliente;
    private $orderDetails;
    private $idCliente;
    private $hasImages = false;
    private $isPaid = false;
    private $uploadedPictures = [];
    private $deletePictureButtons = [];
    private $table;
    public function __construct()
    {
        parent::__construct();
        $this->setLayoutName('admin.proyectos.clientes.detalle-cliente');
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->setBreadcrumb(BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-administrador', 'Home')
            ->addLink(APP_DIRECTORY_PATH . '/lista-de-eventos', 'Eventos')
            ->addLink(APP_DIRECTORY_PATH . '/evento/detalle/' . $this->idEvento, $this->proyecto->nombre_evento)
            ->addActive($this->cliente->nombre_completo));
    }
    private function getLinkPagoEfectivo()
    {
        $link = $this->routes->search('eventosPagoClienteEfectivoView')->getUrl([
            'idEvento' => $this->idEvento,
            'idCliente' => $this->idCliente
        ]);
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => 'btn btn-label-info',
            'target' => '_self',
            // 'target' => '_blank',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Punto de venta pago en efectivo',
            'data-bs-placement' => 'top'
        ], new Icon(['class' => 'fas fa-cash-register mx-2']) . 'Pos');
    }

    private function createServices(array $OrderDetails)
    {
        return new HtmlServices($OrderDetails);
    }

    private function getLinkCargarImagenes()
    {
        $link = $this->routes->search('cargarImagenesClienteView')->getUrl([
            'proyecto' => $this->idEvento,
            'id' => $this->idCliente
        ]);
        return HtmlFactory::create('link', [
            'href' => $link,
            'class' => 'btn btn-label-info',
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => 'Subir las imagenes del cliente al servidor',
            'data-bs-placement' => 'top'
        ], new Icon(['class' => 'fas fa-file-upload mx-2']) . 'Cargar imágenes');
    }

    private function getTable($customers)
    {
        $routes = RouteHelper::make();
        // $eventosPagoClienteEfectivoView = $routes->search('eventosPagoClienteEfectivoView');
        // $eventoClienteDetalleView = $routes->search('eventoClienteDetalleView');
        // $idKey = 'customer_id';
        // $tableId = 'customersEventTable';
        return (new UIOrderDetailsTableService($customers))->get();
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'customerName' => $this->cliente->nombre_completo,
            // 'linkPagoEfectivo' => $this->getLinkPagoEfectivo(),
            'linkPagoEfectivo' => '',
            'linkCargarImagenes' => $this->getLinkCargarImagenes(),
            'hasImages' => $this->hasImages,
            'imagenes' => $this->uploadedPictures,
            'info' => $this->informacionBasicaCliente,
            // 'servicios' => $this->createServices($this->orderDetails),
            'banner' => $this->imgPath,
            'table' => $this->getTable($this->orderDetails),


        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }

    private function initModels()
    {
        $this->idEvento = $this->getData()['idEvento'];
        $this->idCliente = $this->getData()['idCliente'];

        $proyectoModel = new Project();
        $this->proyecto = $proyectoModel->find($this->idEvento);

        $customerModel = new Customer();
        $customer = $customerModel->find($this->idCliente);
        $this->cliente = $customer->getUser();

        $this->informacionBasicaCliente = $customer->getBasicInformationByCustomerId($this->idCliente);
        // echo '<pre>';
        // var_dump($this->informacionBasicaCliente);
        // echo '</pre>';

        $this->orderDetails = Order_detail::getByCustomerAndEvent($this->idCliente, $this->idEvento, \PDO::FETCH_ASSOC);
        // echo '<pre>';
        // var_dump($this->orderDetails);
        // echo '</pre>';

        $this->initPictures();
    }

    private function initPictures()
    {
        $hasUploadedPictures = $this->proyecto->hasUploadedPicturesForUserInProject($this->idCliente);
        if ($hasUploadedPictures) {
            $uploadedPictures = $this->proyecto->getAllPicturesForCustomerInProject($this->idCliente);

            if (is_array($uploadedPictures)) {
            } else {
                $uploadedPictures = [$uploadedPictures];
            }

            foreach ($uploadedPictures as $picture) {
                // echo '<pre>';
                // var_dump($picture);
                // echo '</pre>';
                $imageData = base64_encode(file_get_contents($picture->file_path));
                $src = 'data: ' . mime_content_type($picture->file_path) . ';base64,' . $imageData;

                $image = HtmlFactory::create('img', [
                    'src' => $src,
                    'class' => 'img-thumbnail rounded mx-1',
                    'style' => 'width: 100px;',
                    'alt' => 'Imagen de ' . $this->cliente->nombre_completo
                ]);
                $button = HtmlFactory::create('button', [
                    'type' => 'button',
                    'class' => 'btn btn-danger btn-sm delete-image-button',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-title' => 'Eliminar',
                    'data-bs-placement' => 'top',
                    'data-image-id' => $picture->id
                ], new Icon(['class' => 'far fa-trash-alt']));

                $this->uploadedPictures[] = ['image' => $image, 'button' => $button];
            }
        }
        $this->hasImages = count($this->uploadedPictures) > 0;
    }
    public function draw(...$args)
    {
        $this->setRequest($args["request"]);
        $this->setSession($args["request"]->session());
        $this->setData($args["request"]->getData());
        $this->initModels();
        $this->initBreadcrumb();
        $this->initContent();
        $this->initView();
        $this->response->view($this->getView())->send();
    }
}
