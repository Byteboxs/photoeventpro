<?php

namespace app\services\ui\html;

use app\core\Application;

class HtmlServices
{
    private array $orderDetails = [];
    private $imgPath;

    public function __construct(array $orderDetails)
    {
        $this->orderDetails = $orderDetails;
        $this->imgPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/img/';
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

    private function getIserServiceRow($orderDetail) {}

    private function createProductRow($orderDetail)
    {

        return HtmlFactory::create('div', ['class' => 'col',])
            ->addChild(
                HtmlFactory::create('div', ['class' => 'product-card border border-dark'])
                    ->addChild(
                        HtmlFactory::create('div', ['class' => 'product-img-container'])
                            ->addChild(
                                HtmlFactory::create('img', [
                                    'alt' => $orderDetail->nombre_servicio,
                                    'class' => 'product-img',
                                    'src' => $this->getImageSource($orderDetail->image)
                                ])
                            )
                    )
                    ->addChild(
                        HtmlFactory::create('div', ['class' => 'product-info'])
                            ->addChild(
                                HtmlFactory::create('h3', ['class' => 'product-title'], $orderDetail->nombre_servicio)
                            )
                            ->addChild(
                                HtmlFactory::create('p', ['class' => 'product-price'],  $orderDetail->estado_pago_servicio)
                            )
                        // ->addChild(
                        //     HtmlFactory::create('p', ['class' => 'product-description'], $orderDetail->descripcion_servicio)
                        // )
                    )
            );
    }
    public function render(): string
    {
        $output = '';
        if (count($this->orderDetails) > 0) {
            foreach ($this->orderDetails as $orderDetail) {
                $output .= $this->createProductRow($orderDetail);
            }
        }
        return $output;
    }

    public function __tostring()
    {
        return $this->render();
    }
}
