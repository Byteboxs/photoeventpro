<?php

namespace app\services\portal_vendedor\ui;

use app\core\Application;
use app\core\database\builder\Builder;
use app\helpers\RouteHelper;
use app\services\ui\form\Form;
use app\services\ui\form\FormBuilder;
use app\services\ui\html\HtmlFactory;
use app\services\ui\paginator\Paginator;
use app\services\UIFormsRegisterCustomerService;

class UIPortalVendedorService
{
    private $formBuilder;
    public function __construct()
    {
        $this->formBuilder = new FormBuilder();
    }

    public function getPaginatorEventosDisponibles($result, $page)
    {
        return new Paginator(
            $page,
            RouteHelper::getUrlFor('seleccionarEventoView'),
            $result['currentPage'],
            $result['totalPages'],
            $result['perPage'],
            $result['totalData'],
        );
    }

    public function getTableEventosDisponibles($data)
    {
        $strategy = new EventosVendedorStrategy();
        $columnNames = [
            ['id', '5%'], // project_id
            'evento', // project_name
            // 'start_date', // start_date
            // 'end_date', // end_date
            'hora ceremonia', // ceremony_time
            // 'project_status', // project_status
            // 'institution_name', // institution_name
            'Ubicación', // location_name
            'Estado', // is_habilitado
            'Inicio POS', // habilitation_start_time
            'Fin POS', // habilitation_end_time
        ];
        return (new EventosVendedorTableService($strategy, $data, $columnNames))->get();
    }

    public function getImageSource(string $url)
    {
        $imgPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/img/';
        // $image = basename($url);
        $src = '';
        if ($url == 'service-default-3.webp') {
            $src = $imgPath . $url;
        } else {
            $imageData = base64_encode(file_get_contents($url));
            $src = 'data: ' . mime_content_type($url) . ';base64,' . $imageData;
        }
        return $src;
    }
    public function getImage(string $url, $name)
    {
        return HtmlFactory::create('img', [
            'src' => $this->getImageSource($url),
            'class' => 'rounded-circle',
            'style' => 'width: 100%; height: 100%;',
            'alt' => $name,
        ]);
    }

    public function getProductImage($url, $name)
    {
        return HtmlFactory::create('div', ['class' => 'product-image'])->addChild(
            $this->getImage($url, $name)
        );
    }

    public function getProductDetails($price)
    {
        return HtmlFactory::create('div', [
            'class' => 'product-details'
        ])->addChild(
            HtmlFactory::create('div', [
                'class' => 'product-price'
            ], $price)
        );
    }

    public function getProductItem($producto, $pricingPlan)
    {
        $precio = $producto->precio + ($producto->precio * ($pricingPlan->price / 100));

        return HtmlFactory::create('div', [
            'class' => 'product-item',
            'data-id' => $producto->service_id,
            'data-name' => $producto->service_nombre,
            'data-price' => $precio,
        ])->addChild(
            HtmlFactory::create('div', ['class' => 'item-stock'], 'En stock')
        )->addChild(
            $this->getProductImage($producto->service_image, $producto->service_nombre)
        )->addChild(
            HtmlFactory::create('h6', [], $producto->service_nombre)
        )->addChild(
            HtmlFactory::create('div', ['class' => 'product-category'], $producto->categoria_nombre)
        )->addChild($this->getProductDetails('$ ' . $precio));
    }

    public function getProductosGrid(array $productos, $pricingPlan)
    {
        $grid = '';
        foreach ($productos as $producto) {
            $grid .= $this->getProductItem($producto, $pricingPlan);
        }
        return $grid;
    }

    public function getNewCustomerForm($event_id, $tiposDocumento): Form
    {
        $formService = UIFormsRegisterCustomerService::make();
        return $formService->getForm([
            'project_id' => $event_id,
            'tiposDocumento' => $tiposDocumento
        ]);
    }
}
