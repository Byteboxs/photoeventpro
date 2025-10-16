<?php

namespace app\services\admin\vendedores\ui;

use app\core\Application;
use app\helpers\ArrayModifierHelper;
use app\helpers\RouteHelper;
use app\services\ui\avatars\Avatar;
use app\services\ui\badge\Badge;
use app\services\ui\dropdown\Dropdown;
use app\services\ui\dropdown\LinkDropdownItem;
use app\services\ui\html\HtmlElement;
use app\services\ui\html\HtmlFactory;

class VendedoresStrategy
{

    protected $imgPath;
    private $router;
    private $route;
    private array $colors = [
        'primary',
        'secondary',
        'success',
        'danger',
        'warning',
        'info',
        'dark',
    ];
    public function __construct()
    {
        $this->imgPath = Application::$BASE_PATH . APP_DIRECTORY_PATH . '/public/static/img/';
        $this->router = new RouteHelper();
        $this->route = $this->router->search('servicioPersonalizadoView');
    }

    public function apply(&$data)
    {
        ArrayModifierHelper::addColumn(
            'opciones',
            $this,
            $data,
            [],
            'controlMenu',
            'status'
        );

        ArrayModifierHelper::modifyColumn(
            'status',
            $this,
            $data,
            ['col' => 'status'],
            'modifyStatus'
        );
        ArrayModifierHelper::modifyColumn(
            'nombre_completo',
            $this,
            $data,
            ['col' => 'nombre_completo'],
            'modifyNombreCompleto'
        );
        ArrayModifierHelper::removeColumn('primer_nombre', $data);
        ArrayModifierHelper::removeColumn('segundo_nombre', $data);
        ArrayModifierHelper::removeColumn('primer_apellido', $data);
        ArrayModifierHelper::removeColumn('segundo_apellido', $data);
        ArrayModifierHelper::removeColumn('email', $data);
        ArrayModifierHelper::removeColumn('codigo_tipo_documento', $data);
        ArrayModifierHelper::removeColumn('role_name', $data);
    }

    public function controlMenu($item, $args)
    {
        $user_id = $item['user_id'];
        $url = $this->router->getUrlFor('reporteRendimientoVendedorView', ['user_id' => $user_id]);

        $dropdown = new Dropdown();
        $dropdown->addItem(new LinkDropdownItem($url, 'bx bx-link-external', 'Rendimiento'));
        return $dropdown->render();
    }

    private function addCheckBoxs($item, $args)
    {
        $id = $item['user_id'];
        return '<input type="checkbox" class="form-check-input" name="ids[]" value="' . $id . '"/>';
    }

    private function modifyNombreCompleto($item, $args)
    {
        $user_id = $item['user_id'];
        $nombreCompletoUsuario = $item[$args['col']];
        $nombreCorto = $item['primer_nombre'] . ' ' . $item['primer_apellido'];
        $emailUsuario = $item['email'];

        $url = RouteHelper::getUrlFor('vendedorUpdateView', ['user_id' => $user_id]);
        $tooltip = 'Ir al perfil de ' . $nombreCorto;
        $aleatorio = rand(0, 6);

        $color = $this->colors[$aleatorio];
        $primerCaracter = strtoupper(str_split($item['primer_nombre'])[0]);
        $segundoCaracter = strtoupper(str_split($item['primer_apellido'])[0]);
        $avatarContent = $primerCaracter . $segundoCaracter;
        $avatar = new Avatar(
            $avatarContent,
            $nombreCompletoUsuario,
            $emailUsuario,
            $url,
            $tooltip,
            $color
        );
        $avatar->setTareget('_self');
        return $avatar;
    }

    private function modifyStatus($item, $args)
    {
        $status = $item[$args['col']];
        $color = $status == 'activo' ? 'success' : 'danger';

        return new Badge($status, $color);
    }

    private function getLink($linkText, $url): HtmlElement
    {
        return HtmlFactory::create('a', ['href' => $url], $linkText);
    }
    private function getImage($image, $alt, $tootip = 'Ver imagenes personalizadas para el servicio'): HtmlElement
    {
        if ($image == 'service-default-3.webp') {
            $image = $this->imgPath . $image;
        }
        $imageData = base64_encode(file_get_contents($image));
        $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
        return HtmlFactory::create('img', [
            'src' => $src,
            'class' => 'img-thumbnail rounded mx-1',
            'style' => 'width: 40px;',
            'alt' => $alt,
        ]);
    }
    public function modifyEstadoPagoServicio($item, $args)
    {
        $estadoPago = $item['estado_pago_servicio'];
        $label = $estadoPago;
        $color = $estadoPago == 'pagado' ? 'success' : 'danger';

        return new Badge($label, $color);
    }
    public function modifyEstadoSeleccionFotos($item, $args)
    {
        $estado = $item['estado_seleccion_fotos'];
        $label = $estado == 'seleccionadas' ? 'Si' : 'No';
        $color = $estado == 'seleccionadas' ? 'success' : 'danger';

        return new Badge($label, $color);
    }
    public function modifyImage($item, $args)
    {
        $imageFile = $item['image'];
        $estadoSeleccionFotos = $item['estado_seleccion_fotos'];
        $nombreServicio = $item['nombre_servicio'];

        $image = $this->getImage($imageFile, $item['nombre_servicio'], 'Detalle servicio ' . $nombreServicio);
        $container = HtmlFactory::create(
            'div',
            [
                'class' => 'd-flex justify-content-start align-items-center user-name'
            ]
        );
        $avatarWraper = HtmlFactory::create(
            'div',
            [
                'class' => 'avatar-wrapper'
            ]
        )->addChild(
            HtmlFactory::create(
                'div',
                [
                    'class' => 'avatar avatar-sm me-4'
                ]
            )->addChild($image)
        );
        $service = HtmlFactory::create(
            'div',
            [
                'class' => 'd-flex flex-column'
            ]
        )->addChild(
            HtmlFactory::create(
                'span',
                [
                    'class' => 'fw-large'
                ],
                $nombreServicio
            )
        );

        $container->addChild($avatarWraper);
        $container->addChild($service);

        if ($estadoSeleccionFotos == 'seleccionadas') {
            $url = $this->router->search('servicioPersonalizadoView')->getUrl(
                [
                    'order_detail_id' => $item['id'],
                ]
            );
            $link = HtmlFactory::create(
                'a',
                [
                    'href' => $url,
                    'style' => 'color: var( --bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)))',
                    'target' => '_blank',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                    'data-bs-original-title' => 'Ir a Detalle servicio ' . $nombreServicio,
                ]
            );
            $link->addChild($container);
            return $link;
        } else {
            return $container;
        }
    }

    public function modifyEstatusEntrega($item, $args)
    {
        $estado = $item[$args['col']];
        $color = 'success';
        $label = 'Entregado';
        if ($estado == 'pendiente') {
            $color = 'danger';
            $label = 'Pendiente';
        } else if ($estado == 'en_preparacion') {
            $color = 'warning';
            $label = 'En preparacion';
        } else if ($estado == 'enviado') {
            $color = 'info';
            $label = 'Enviado';
        }
        return new Badge($label, $color);
    }
    public function modifyEstadoPagoCompra($item, $args)
    {
        $estado = $item[$args['col']];
        $color = 'success';
        if ($estado == 'pendiente') {
            $color = 'danger';
        } else if ($estado == 'cancelado') {
            $color = 'dark';
        }
        return new Badge($estado, $color);
    }

    public function modifyNombreCategoria($item, $args)
    {
        $categoria = ucfirst(mb_strtolower($item[$args['col']]));
        $ico = HtmlFactory::create('icon', [
            'class' => 'bx bxs-printer',
            'style' => 'color: var(--bs-primary); font-size: 24px;'
        ]);
        if ($categoria !== 'Impresion') {
            $ico = HtmlFactory::create('icon', [
                'class' => 'bx bxs-save',
                'style' => 'color: var(--bs-success); font-size: 24px;'
            ]);
        }
        return $ico;
    }

    public function modifyNombreEventoProyecto($item, $args)
    {
        $nombreEvento = ucfirst(mb_strtolower($item[$args['col']]));
        $idEvento = $item['project_id'];
        $url = $this->router->search('eventoDetalleView')->getUrl(
            [
                'proyecto' => $idEvento
            ]
        );
        $tooltip = 'Ir a Detalle Evento ' . $nombreEvento;
        $aleatorio = rand(0, 6);
        $colors = [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'dark',
        ];
        $color = $colors[$aleatorio];
        $eventoArray = explode(' ', $nombreEvento);
        $primerCaracter = strtoupper(str_split($eventoArray[0])[0]);
        $segundoCaracter = strtoupper(str_split($eventoArray[1])[0]);
        $avatarContent = $primerCaracter . $segundoCaracter;
        $avatar = new Avatar(
            $avatarContent,
            $nombreEvento,
            '',
            $url,
            $tooltip,
            $color
        );
        $avatar->setTareget('_blank');
        return $avatar;
    }


    public function modifyNombreCompletoUsuario($item, $args)
    {
        $nombreCompletoUsuario = $item[$args['col']];
        $emailUsuario = $item['email_usuario'];
        $idEvento = $item['project_id'];
        $idCliente = $item['customer_id'];
        $url = $this->router->search('eventoClienteDetalleView')->getUrl(
            [
                'idEvento' => $idEvento,
                'idCliente' => $idCliente
            ]
        );
        $tooltip = 'Ir a Detalle Cliente ' . $nombreCompletoUsuario;

        $aleatorio = rand(0, 6);
        $colors = [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'dark',
        ];
        $color = $colors[$aleatorio];
        $primerCaracter = strtoupper(str_split($item['primer_nombre_usuario'])[0]);
        $segundoCaracter = strtoupper(str_split($item['primer_apellido_usuario'])[0]);
        $avatarContent = $primerCaracter . $segundoCaracter;
        $avatar = new Avatar(
            $avatarContent,
            $nombreCompletoUsuario,
            $emailUsuario,
            $url,
            $tooltip,
            $color
        );
        $avatar->setTareget('_blank');
        return $avatar;
    }
    public function modifyImageOrders($item, $args)
    {
        $imageFile = $item[$args['col']];
        $nombreServicio = $item['nombre_servicio'];
        $estadoSeleccionFotos = $item['estado_seleccion_fotos'];

        $image = $this->getImage($imageFile, $item['nombre_servicio'], 'Detalle servicio ' . $nombreServicio);


        $container = HtmlFactory::create(
            'div',
            [
                'class' => 'd-flex justify-content-start align-items-center user-name'
            ]
        );
        $avatarWraper = HtmlFactory::create(
            'div',
            [
                'class' => 'avatar-wrapper'
            ]
        )->addChild(
            HtmlFactory::create(
                'div',
                [
                    'class' => 'avatar avatar-sm me-4'
                ]
            )->addChild($image)
        );
        $service = HtmlFactory::create(
            'div',
            [
                'class' => 'd-flex flex-column'
            ]
        )->addChild(
            HtmlFactory::create(
                'span',
                [
                    'class' => 'fw-large'
                ],
                $nombreServicio
            )
        );

        $container->addChild($avatarWraper);
        $container->addChild($service);

        if ($estadoSeleccionFotos == 'seleccionadas') {
            $url = $this->router->search('servicioPersonalizadoView')->getUrl(
                [
                    'order_detail_id' => $item['order_detail_id'],
                ]
            );
            $link = HtmlFactory::create(
                'a',
                [
                    'href' => $url,
                    'style' => 'color: var( --bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)))',
                    'target' => '_blank',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                    'data-bs-original-title' => 'Ir a Detalle servicio ' . $nombreServicio,
                ]
            );
            $link->addChild($container);
            return $link;
        } else {
            return $container;
        }
    }
}
