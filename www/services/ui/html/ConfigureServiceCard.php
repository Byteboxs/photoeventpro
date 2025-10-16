<?php

namespace app\services\ui\html;

use app\core\Application;
use app\helpers\RouteHelper;
use app\services\ui\html\HtmlFactory;

class ConfigureServiceCard
{
    private $data;
    private $imgPath;
    private $routes;
    private $route;
    public function __construct($data)
    {
        $this->data = $data;
        $this->imgPath = Application::$BASE_URL . APP_DIRECTORY_PATH . '/public/static/img/';
        $this->routes = new RouteHelper();
        $this->route = $this->routes->search('imageSelectorAppView');
    }

    private function createSubtitleSpan(string $categoria)
    {
        if ($categoria == "Impresion") {
            $icono = HtmlFactory::create('i', ['class' => 'fas fa-print']);
            $span = HtmlFactory::create('span', ['class' => 'bg-label-info']);
            $span->addChild($icono);
            return $span;
        } else {
            $icono = HtmlFactory::create('i', ['class' => 'fas fa-download']);
            $span = HtmlFactory::create('span', ['class' => 'bg-label-success']);
            $span->addChild($icono);
            $span->addChild($icono);
            return $span;
        }
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

    private function getImage($file, $alt)
    {
        $src = $this->getImageSource($file);
        return HtmlFactory::create('img', [
            'alt' => $alt,
            // 'style' => 'height:100px;',
            'class' => 'img-fluid d-flex mx-auto my-6 rounded',
            'src' => $src
        ]);
    }

    private function getLink($url, string $estadoServicio)
    {
        // TODO: Estado inactivo para el boton
        if ($estadoServicio == 'inactivo') {
            return HtmlFactory::create('button', [
                // 'href' => $url,
                'class' => 'btn btn-outline-dark',
                'data-bs-toggle' => 'tooltip',
                'data-bs-title' => 'Este servicio se encuentra temporalmente inactivo',
                // 'disabled' => ''
            ], 'Inactivo');
        } else if ($estadoServicio == 'lectura') {
            return HtmlFactory::create(
                'link',
                [
                    'href' => $url,
                    'class' => 'btn btn-outline-success',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-title' => 'Ingresa para ver tus imagenes',
                ],
                'Ver'
            );
        } else {
            return HtmlFactory::create(
                'link',
                [
                    'href' => $url,
                    'class' => 'btn btn-outline-primary',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-title' => 'Ingresa y configura tus imagenes',
                ],
                'Configurar'
            );
        }
    }

    private function translateCategoryTitle($nombreCategoria)
    {
        $output = ' Producto digital';
        if ($nombreCategoria == 'Impresion') {
            $output = ' Producto impreso';
        }
        return $output;
    }
    public function render(): string
    {
        $url = $this->route->getUrl(['order_detail_id' => $this->data->order_detail_id]);
        $title = HtmlFactory::create('h5', [], $this->data->nombre_servicio);
        $subTitle = HtmlFactory::create('h6', ['class' => 'card-subtitle']);
        $subTitle->addChild($this->createSubtitleSpan($this->data->nombre_categoria));
        $subTitle->addChild($this->translateCategoryTitle($this->data->nombre_categoria));
        $image = $this->getImage($this->data->image, $this->data->nombre_servicio);
        $cardText = HtmlFactory::create('p', [], $this->data->descripcion_servicio);
        $link = $this->getLink($url, $this->data->estado_servicio);
        $body = HtmlFactory::create('div', ['class' => 'card-body']);
        $body->addChild($title)
            ->addChild($subTitle)
            ->addChild($image)
            ->addChild($cardText)
            ->addChild($link);
        $card = HtmlFactory::create('div', ['class' => 'card h-100']);
        $card->addChild($body);
        $col = HtmlFactory::create('div', ['class' => 'col-md-6 col-lg-3'])
            ->addChild($card);
        return $col;
    }
    public function __tostring()
    {
        return $this->render();
    }
}

// '<div class="card h-100">
//             <div class="card-body">
//                 <h5 class="card-title">Foto album</h5>
//                 <h6 class="card-subtitle">
//                     <span class="badge bg-label-success">
//                         <i class="fas fa-download"></i>
//                     </span> Impresion
//                     </h6>
//                 <img class="img-fluid d-flex mx-auto my-6 rounded" src="http://localhost:8080/photoeventpro/public/static/img/service-default-3.webp" alt="Card image cap">
//                 <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, quasi. Veniam eum est excepturi sint ullam vitae, possimus nam quae autem natus non inventore libero eligendi, consectetur quidem pariatur eaque?</p>
//                 <a href="javascript:void(0)" class="btn btn-outline-primary">Descargar</a>
//             </div>
//         </div>';
