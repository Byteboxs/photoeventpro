<?php

namespace app\controllers\customer;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\helpers\RouteHelper;
use app\models\Order_detail;
use app\models\Selected_picture;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\AlbumPage;
use app\services\ui\html\AlbumSheet;
use app\services\ui\html\HtmlFactory;

class UIServicioPersonalizadoController extends SneatAdvancedController implements PageBuilder
{
    private $serviceInfo;
    private $customerEventInfo;
    private $order_detail_id;
    private $selectedPictures;
    private $hasSelectedPictures;
    private $router;
    private $route;
    private $idEvento;
    private $idCliente;
    private $nombreCompletoUsuario;
    private $nombreCortoUsuario;
    private $nombreEvento;

    public function __construct()
    {
        parent::__construct();
        $this->router = new RouteHelper();

        $this->setLayoutName('customer.service.content');
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink($this->routes->search('adminDashboardView')->getUrl(), 'Home')
            ->addLink($this->router->search('eventosView')->getUrl(), 'Eventos')
            ->addLink($this->router->search('eventoDetalleView')->getUrl([
                'proyecto' => $this->idEvento,
            ]), $this->nombreEvento)
            ->addLink($this->router->search('eventoClienteDetalleView')->getUrl([
                'idEvento' => $this->idEvento,
                'idCliente' => $this->idCliente,
            ]), $this->nombreCortoUsuario)
            ->addActive('Selección');
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'selectedPictures' => $this->getPreviewAlbum(),
            'nombreCompleto' => $this->nombreCompletoUsuario,
            'servicio' => $this->serviceInfo->nombre,
            'evento' => $this->nombreEvento,
        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }
    private function initModels()
    {
        $this->order_detail_id = $this->getData()['order_detail_id'];
        $this->selectedPictures = Selected_picture::getPicturesByOrderDetailId($this->order_detail_id);
        $this->hasSelectedPictures = count($this->selectedPictures) > 0;
        $this->serviceInfo = Order_detail::getServiceByOrderDetailId($this->order_detail_id);
        $this->customerEventInfo = Order_detail::getCustomerEventInfoByOrderDetailId($this->order_detail_id);
        $this->idEvento = $this->customerEventInfo->project_id;
        $this->idCliente = $this->customerEventInfo->customer_id;
        $this->nombreCompletoUsuario = $this->customerEventInfo->nombre_completo_usuario;
        $this->nombreCortoUsuario = $this->customerEventInfo->primer_nombre_usuario . ' ' . $this->customerEventInfo->primer_apellido_usuario;
        $this->nombreEvento = $this->customerEventInfo->nombre_evento_proyecto;
    }

    private function getPreviewAlbum()
    {
        $shets = '';
        if ($this->hasSelectedPictures) {
            $index = 1;
            while ($index <= (count($this->selectedPictures) / 2)) {
                $sheet = new AlbumSheet();
                $firstPageNumber = ($index - 1) * 2;
                $page = new AlbumPage($this->selectedPictures[$firstPageNumber]->thumbnail_file, $this->selectedPictures[$firstPageNumber]->order_index);
                $sheet->addPage($page);
                $secondPageNumber = $firstPageNumber + 1;
                $page = new AlbumPage($this->selectedPictures[$secondPageNumber]->thumbnail_file, $this->selectedPictures[$secondPageNumber]->order_index);
                $sheet->addPage($page);
                $shets .= $sheet;
                $index++;
            }
        }
        return $shets;
    }
    private function getImages($images)
    {
        $output = '';
        if ($images) {
            foreach ($images as $image) {
                $imageData = base64_encode(file_get_contents($image->thumbnails_path));
                $src = 'data: ' . mime_content_type($image->thumbnails_path) . ';base64,' . $imageData;
                $img = HtmlFactory::create('img', [
                    'src' => $src,
                    'id' => $image->picture_id,
                    'data-img-id' => $image->picture_id,
                    'class' => 'preloaded-image img-fluid',
                    'draggable' => 'true',
                ]);
                $output .= HtmlFactory::create('div', [
                    'class' => 'col-6',
                ])->addChild($img);
            }
        }
        return $output;
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
        // echo '<pre>';
        // var_dump($this->serviceInfo);
        // echo '</pre>';
        $this->response->view($this->getView())->send();
    }
}
