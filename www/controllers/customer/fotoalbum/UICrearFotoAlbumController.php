<?php

namespace app\controllers\customer\fotoalbum;

use app\controllers\PageBuilder;
use app\controllers\template\SneatAdvancedController;
use app\core\views\View;
use app\models\Customer;
use app\models\Order_detail;
use app\models\Selected_picture;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\html\AlbumPage;
use app\services\ui\html\AlbumSheet;
use app\services\ui\html\HtmlFactory;

class UICrearFotoAlbumController extends SneatAdvancedController implements PageBuilder
{
    private $order_detail_id;
    private $maxImagesAlloewd;
    private $images;
    private $selectedPictures;
    private $hasSelectedPictures;
    private $customer;
    private $serviceInfo;
    public function __construct()
    {
        parent::__construct();
        $this->setLayoutName('customer.fotoalbum.content');
    }
    public function initBreadcrumb(array $args = [])
    {
        $this->breadcrumb = BreadcrumbBuilderService::create()
            ->addLink(APP_DIRECTORY_PATH . '/dashboard-cliente', 'Home')
            ->addActive($this->serviceInfo->nombre);
    }
    public function initContent(array $args = [])
    {
        $this->setContent(new View($this->getLayoutName(), [
            'maxPages' => ($this->maxImagesAlloewd),
            'orderDetailId' => $this->order_detail_id,
            'images' => $this->getImages($this->images),
            'hasSelectedPictures' => $this->hasSelectedPictures,
            'selectedPictures' => $this->getPreviewAlbum(),
            'nombreCompleto' => $this->customer->nombre_completo,
            'email' => $this->customer->email,
            'evento' => $this->customer->proyecto_nombre,
            'servicio' => $this->serviceInfo->nombre,
        ]));
    }
    public function initView(array $args = [])
    {
        $this->setView($this->create($this->getSession(), $this->getBreadcrumb(), $this->getContent()));
    }
    private function initModels()
    {
        $this->order_detail_id = $this->getData()['order_detail_id'];
        $this->maxImagesAlloewd = Order_detail::getMaxNumImagesAllowed($this->order_detail_id);
        $this->images = Customer::getImagenesProyectoActivoByUserId($this->getSession()->get('userId'));
        $this->selectedPictures = Selected_picture::getPicturesByOrderDetailId($this->order_detail_id);
        $this->hasSelectedPictures = count($this->selectedPictures) > 0;
        $this->customer = Customer::getAdvancedInformationByUserId($this->getSession()->get('userId'));
        $this->serviceInfo = Order_detail::getServiceByOrderDetailId($this->order_detail_id);
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
