<?php

namespace app\services\ui\html;

use app\core\Application;
use app\helpers\RouteHelper;

class HtmlServiceCardPanelCreator
{
    private ?array $orderDetails = [];
    private $imgPath;
    private $routes;
    private $route;

    public function __construct($orderDetails)
    {
        $this->orderDetails = $orderDetails;
        $this->imgPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/img/';
        $this->routes = new RouteHelper();
        $this->route = $this->routes->search('imageSelectorAppView');
    }

    private function creteAlert()
    {
        return '<div class="alert alert-warning d-flex align-items-center my-3 fs-4" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="ms-2">¡Atención! No cuenta con servicios.</div>
            </div>';
    }
    private function getImageSource($image)
    {
        $src = '';
        if ($image == 'service-default-3.webp') {
            $src = $this->imgPath . $image;
        } else {
            $imageData = base64_encode(file_get_contents($image));
            $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
        }
        return $src;
    }
    private function getRow(int $cols)
    {
        return HtmlFactory::create('div', [
            'class' => 'row row-cols-1 row-cols-md-2 row-cols-lg-' . $cols . ' g-4 mt-3',
        ]);
    }

    private function getEditLink($orderDetailId, $tooltip)
    {
        $url = $this->route->getUrl(['order_detail_id' => $orderDetailId]);
        return HtmlFactory::create('link', [
            'href' => $url,
            'data-bs-toggle' => 'tooltip',
            'data-bs-title' => $tooltip,
        ]);
    }

    private function getDiscountBadge($discount)
    {
        return HtmlFactory::create('div', ['class' => 'discount-badge'], $discount);
    }
    private function getImage($file, $alt)
    {
        $src = $this->getImageSource($file);
        return HtmlFactory::create('img', [
            'alt' => $alt,
            'class' => 'product-img',
            'src' => $src
        ]);
    }
    private function getStockIndicator($stockIndicator)
    {
        return HtmlFactory::create('div', ['class' => 'stock-indicator in-stock'], $stockIndicator);
    }
    private function getTitle($title)
    {
        return HtmlFactory::create('h3', ['class' => 'product-title'], $title);
    }
    private function getProductPrice($price)
    {
        return HtmlFactory::create('p', ['class' => 'product-price'], $price);
    }
    private function getProductRating($ratingStarts, $ratingCount)
    {
        //★★★★☆
        //★★★★½
        return HtmlFactory::create('div', ['class' => 'product-rating'])
            ->addChild(
                HtmlFactory::create('div', ['class' => 'rating-stars'], $ratingStarts)
            )->addChild(
                HtmlFactory::create('span', ['class' => 'rating-count'], $ratingCount)
            );
    }
    private function getProductDescription($description)
    {
        return Htmlfactory::create('p', ['class' => 'product-description'], $description);
    }
    private function getDisabledOverlay($message)
    {
        return HtmlFactory::create('div', ['class' => 'disabled-overlay'])
            ->addChild(
                HtmlFactory::create('p', ['class' => 'disabled-message'], $message)
            );
    }

    public function render(): string
    {
        $output = '';
        $cols = $this->getRow(4);
        if (is_array($this->orderDetails) && count($this->orderDetails) > 0) {
            foreach ($this->orderDetails as $orderDetail) {
                $serviceCard = new HtmlServiceCard();
                if ($orderDetail->estado_servicio == 'edicion') {
                    $link = $this->getEditLink($orderDetail->order_detail_id, 'Haga click 
                    aca para seleccionar las imagenes.');
                    $serviceCard->setImage($this->getImage($orderDetail->image, $orderDetail->nombre_servicio));
                    $serviceCard->setStockIndicator($this->getStockIndicator('<i class="far fa-images"></i> 
                    Configurar servicio'));
                    $serviceCard->setTitle($this->getTitle($orderDetail->nombre_servicio));
                    $serviceCard->setProductDescription($this->getProductDescription($orderDetail->descripcion_servicio));
                    $row = $link->addChild($serviceCard);
                    $cols->addChild($row);
                }
                if ($orderDetail->estado_servicio == 'lectura') {
                    $link = $this->getEditLink($orderDetail->order_detail_id, 'Haga click 
                    aca para ver las imagenes.');
                    $serviceCard->setImage($this->getImage($orderDetail->image, $orderDetail->nombre_servicio));
                    $serviceCard->setStockIndicator($this->getStockIndicator('<i class="fas fa-camera-retro"></i>'));
                    $serviceCard->setTitle($this->getTitle($orderDetail->nombre_servicio));
                    $serviceCard->setProductDescription($this->getProductDescription($orderDetail->descripcion_servicio));
                    $serviceCard->toogleCardType();
                    $serviceCard->setDissabledMessage('Este servicio ya fue configurado.');
                    $row = $link->addChild($serviceCard);
                    $cols->addChild($row);
                }
                if ($orderDetail->estado_servicio == 'inactivo') {
                    $serviceCard->setImage($this->getImage($orderDetail->image, $orderDetail->nombre_servicio));
                    $serviceCard->setStockIndicator($this->getStockIndicator('<i class="fas fa-camera-retro"></i>'));
                    $serviceCard->setTitle($this->getTitle($orderDetail->nombre_servicio));
                    $serviceCard->setProductDescription($this->getProductDescription($orderDetail->descripcion_servicio));
                    $serviceCard->toogleCardType();
                    $serviceCard->setDissabledMessage('Este servicio no esta disponible.');
                    $cols->addChild($serviceCard);
                }
            }
        } else {
            $output = $this->creteAlert();
        }
        return $cols;
    }

    public function __tostring()
    {
        return $this->render();
    }
}
