<?php

namespace app\helpers;

use app\controllers\admin\clientes\ClientesController;
use app\controllers\admin\dashboard\UIAdminDashboardController;
use app\controllers\admin\productos\ProductosController;
use app\controllers\admin\proyectos\ProyectosController;
use app\controllers\admin\vendedores\VendedoresController;
use app\controllers\customer\CustomerController;
use app\controllers\order\OrdersController;
use app\controllers\portal_vendedor\PortalVendedorController;
use app\core\collections\Map;
use app\core\Singleton;

class RouteHelper extends Singleton implements RouterAssistant
{
    private $actualRoute = null;
    private $routes;
    private $appFolderAddress = APP_DIRECTORY_PATH;

    public function __construct()
    {
        parent::__construct();
        $this->routes = new Map();
        $this->init();
    }

    private function init()
    {
        $this->routes->add('dashboard', new Map([
            'type' => 'get',
            'route' => '/dashboard-administrador',
            'url' => $this->appFolderAddress . '/dashboard-administrador',
            'controller' => UIAdminDashboardController::class,
            'method' => 'adminDashboardView',
            'isRule' => false,
            'rules' => []
        ]));

        $this->routes->add('formularioCrearProyectoView', new RouteModel(
            'get',
            '/crear-proyecto',
            $this->appFolderAddress . '/crear-proyecto',
            ProyectosController::class,
            'formularioCrearProyectoView',
            false,
            []
        ));

        $this->routes->add('editarEventoView', new RouteModel(
            'get',
            '/eventos/evento/{evento_id}/editar',
            $this->appFolderAddress . '/eventos/evento/{evento_id}/editar',
            ProyectosController::class,
            'editarEventoView',
            true,
            ['evento_id' => "/^[0-9]+$/"]
        ));

        $this->routes->add('eventosPagoClienteEfectivoView', new RouteModel(
            'get',
            '/eventos/{idEvento}/pagos/cliente/{idCliente}/efectivo',
            $this->appFolderAddress . '/eventos/{idEvento}/pagos/cliente/{idCliente}/efectivo',
            ProyectosController::class,
            'eventosPagoClienteEfectivoView',
            true,
            ['idEvento' => "/^[0-9]+$/", 'idCliente' => "/^[0-9]+$/"]
        ));

        $this->routes->add('cargarImagenesClienteView', new RouteModel(
            'get',
            '/evento/{proyecto}/cliente/{id}/cargar-imagenes',
            $this->appFolderAddress . '/evento/{proyecto}/cliente/{id}/cargar-imagenes',
            ProyectosController::class,
            'cargarImagenesClienteView',
            true,
            ['proyecto' => "/^[0-9]+$/", 'id' => "/^[0-9]+$/"]
        ));
        $this->routes->add('eventoDetalleView', new RouteModel(
            'get',
            '/evento/detalle/{proyecto}',
            $this->appFolderAddress . '/evento/detalle/{proyecto}',
            ProyectosController::class,
            'eventoDetalleView',
            true,
            ['proyecto' => "/^[0-9]+$/"]
        ));

        $this->routes->add('eventosView', new RouteModel(
            'get',
            '/lista-de-eventos',
            $this->appFolderAddress . '/lista-de-eventos',
            ProyectosController::class,
            'eventosView',
            false,
            []
        ));
        $this->routes->add('eventoClienteDetalleView', new RouteModel(
            'get',
            '/eventos/{idEvento}/cliente/{idCliente}/detalle',
            $this->appFolderAddress . '/eventos/{idEvento}/cliente/{idCliente}/detalle',
            ProyectosController::class,
            'eventoClienteDetalleView',
            true,
            ['idEvento' => "/^[0-9]+$/", 'idCliente' => "/^[0-9]+$/"]
        ));
        $this->routes->add('servicioPersonalizadoView', new RouteModel(
            'get',
            '/servicio/personalizado/{order_detail_id}',
            $this->appFolderAddress . '/servicio/personalizado/{order_detail_id}',
            CustomerController::class,
            'servicioPersonalizadoView',
            true,
            ['order_detail_id' => "/^[0-9]+$/"]
        ));
        $this->routes->add('adminDashboardView', new RouteModel(
            'get',
            '/dashboard-administrador',
            $this->appFolderAddress . '/dashboard-administrador',
            ProyectosController::class,
            'adminDashboardView',
            false,
            []
        ));
        $this->routes->add('crearProductoView', new RouteModel(
            'get',
            '/crear-producto',
            $this->appFolderAddress . '/crear-producto',
            ProductosController::class,
            'crearProductoView',
            false,
            []
        ));
        $this->routes->add('ordersView', new RouteModel(
            'get',
            '/orders',
            $this->appFolderAddress . '/orders',
            OrdersController::class,
            'ordersView',
            false,
            []
        ));
        $this->routes->add('vendedoresView', new RouteModel(
            'get',
            '/vendedores',
            $this->appFolderAddress . '/vendedores',
            VendedoresController::class,
            'vendedoresView',
            false,
            []
        ));
        $this->routes->add('registrarVendedorView', new RouteModel(
            'get',
            '/vendedores/registrar',
            $this->appFolderAddress . '/vendedores/registrar',
            VendedoresController::class,
            'vendedoresRegistrarView',
            false,
            []
        ));
        $this->routes->add('reporteRendimientoVendedorView', new RouteModel(
            'get',
            '/vendedores/vendedor/{user_id}/rendimiento',
            $this->appFolderAddress . '/vendedores/vendedor/{user_id}/rendimiento',
            VendedoresController::class,
            'reporteRendimientoVendedorView',
            true,
            ['user_id' => "/^[0-9]+$/"]
        ));
        $this->routes->add('vendedorUpdateView', new RouteModel(
            'get',
            '/vendedores/vendedor/{user_id}/update',
            $this->appFolderAddress . '/vendedores/vendedor/{user_id}/update',
            VendedoresController::class,
            'vendedorUpdateView',
            true,
            ['user_id' => "/^[0-9]+$/"]
        ));
        $this->routes->add('vendedorUpdateAction', new RouteModel(
            'post',
            '/vendedores/vendedor/update',
            $this->appFolderAddress . '/vendedores/vendedor/update',
            VendedoresController::class,
            'vendedorUpdateAction',
            false,
            []
        ));
        $this->routes->add('asignarServiciosEventoView', new RouteModel(
            'get',
            '/eventos/{evento_id}/servicios/asignar',
            $this->appFolderAddress . '/eventos/{evento_id}/servicios/asignar',
            ProyectosController::class,
            'asignarServiciosEventoView',
            true,
            ['evento_id' => "/^[0-9]+$/"]
        ));
        $this->routes->add('eventoVendedoresView', new RouteModel(
            'get',
            '/eventos/{evento_id}/vendedores/asignar',
            $this->appFolderAddress . '/eventos/{evento_id}/vendedores/asignar',
            ProyectosController::class,
            'eventoVendedoresView',
            true,
            ['evento_id' => "/^[0-9]+$/"]
        ));
        $this->routes->add('eventoVendedoresSaveAction', new RouteModel(
            'post',
            '/eventos/vendedores/save',
            $this->appFolderAddress . '/eventos/vendedores/save',
            ProyectosController::class,
            'eventoVendedoresSaveAction',
            false,
            []
        ));
        $this->routes->add('eventoVendedorDeleteAction', new RouteModel(
            'post',
            '/eventos/vendedor/delete',
            $this->appFolderAddress . '/eventos/vendedor/delete',
            ProyectosController::class,
            'eventoVendedorDeleteAction',
            false,
            []
        ));
        $this->routes->add('imageSelectorAppView', new RouteModel(
            'get',
            '/personalizar-servicio/{order_detail_id}',
            $this->appFolderAddress . '/personalizar-servicio/{order_detail_id}',
            CustomerController::class,
            'imageSelectorAppView',
            true,
            ['order_detail_id' => "/^[0-9]+$/"]
        ));
        $this->routes->add('selectServicePicturesAction', new RouteModel(
            'post',
            '/servicio/seleccionar-fotos',
            $this->appFolderAddress . '/servicio/seleccionar-fotos',
            CustomerController::class,
            'selectServicePicturesAction',
            false,
            []
        ));
        $this->routes->add('productosView', new RouteModel(
            'get',
            '/listado-de-productos',
            $this->appFolderAddress . '/listado-de-productos',
            ProductosController::class,
            'productosView',
            false,
            []
        ));
        $this->routes->add('productosViewPaginado', new RouteModel(
            'get',
            '/listado-de-productos/page/{page}',
            $this->appFolderAddress . '/listado-de-productos/page/{page}',
            ProductosController::class,
            'productosView',
            true,
            ['page' => "/^[0-9]+$/"]
        ));
        $this->routes->add('formularioAgregarClienteProyectoView', new RouteModel(
            'get',
            '/registrar-cliente-evento/{proyecto}',
            $this->appFolderAddress . '/registrar-cliente-evento/{proyecto}',
            ClientesController::class,
            'formularioAgregarClienteProyectoView',
            true,
            ['proyecto' => "/^[0-9]+$/"]
        ));
        // MODULO PORTAL DE VENTAS--------------
        $this->routes->add('salesPersonDashboardView', new RouteModel(
            'get',
            '/dashboard-vendedor',
            $this->appFolderAddress . '/dashboard-vendedor',
            PortalVendedorController::class,
            'salesPersonDashboardView',
            false,
            []
        ));
        $this->routes->add('seleccionarEventoView', new RouteModel(
            'get',
            '/portal/vendedor/eventos',
            $this->appFolderAddress . '/portal/vendedor/eventos',
            PortalVendedorController::class,
            'seleccionarEventoView',
            false,
            []
        ));
        $this->routes->add('posView', new RouteModel(
            'get',
            '/portal/vendedor/evento/{evento_id}/pos',
            $this->appFolderAddress . '/portal/vendedor/evento/{evento_id}/pos',
            PortalVendedorController::class,
            'posView',
            true,
            ['evento_id' => "/^[0-9]+$/"]
        ));
        // --------------------------------------

        $this->routes->add('eventosClientesSubirImagenesAction', [
            'type' => 'post',
            'route' => '/eventos/clientes/subir-imagenes',
            'url' => $this->appFolderAddress . '/eventos/clientes/subir-imagenes',
            'controller' => ProyectosController::class,
            'method' => 'eventosClientesSubirImagenesAction',
            'isRule' => false,
            'rules' => []
        ]);

        $this->routes->add('eliminarImagenClienteAction', new RouteModel(
            'post',
            '/eventos/clientes/eliminar-imagen-almacenada',
            $this->appFolderAddress . '/eventos/clientes/eliminar-imagen-almacenada',
            ProyectosController::class,
            'eliminarImagenClienteAction',
            false,
            []
        ));
    }

    public static function getUrlFor($route, $params = [])
    {
        $self = new self();
        $route = $self->search($route);
        if ($route == null) {
            return null;
        }
        return $route->getUrl($params);
    }

    public function search($route): ?RouteModel
    {
        if ($this->hasRoute($route)) {
            $this->actualRoute = $route;
            return $this->routes->get($this->actualRoute);
        }
        return null;
    }

    public function getRoute($router, $route)
    {
        $actualRoute = self::search($route);
        if ($actualRoute && $actualRoute->getIsRule()) {
            $router->{$actualRoute->getType()}($actualRoute->getRoute(), [$actualRoute->getController(), $actualRoute->getMethod()])->where($actualRoute->getRules());
        } else {
            $router->{$actualRoute->getType()}($actualRoute->getRoute(), [$actualRoute->getController(), $actualRoute->getMethod()]);
        }
    }

    public function hasRoute($route)
    {
        return $this->routes->contains($route);
    }
}
