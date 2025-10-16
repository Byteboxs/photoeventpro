<?php

use app\core\database\builder\Where;
// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../vendor/autoload.php';

use app\controllers\admin\clientes\ClientesController;
use app\controllers\admin\dashboard\UIAdminDashboardController;
use app\controllers\admin\productos\ProductosController;
use app\controllers\admin\proyectos\ProyectosController;
use app\controllers\admin\vendedores\VendedoresController;
use app\controllers\auth\AuthController;
use app\controllers\auth\UIAuthController;
use app\controllers\customer\CustomerController;
use app\controllers\customer\dashboard\UIClienteDashboardController;
use app\controllers\customer\store\UIStoreController;
use app\controllers\FrameworkController;
use app\controllers\order\OrdersController;
use app\controllers\portal_vendedor\PortalVendedorController;
use app\controllers\TestController;
use app\core\Path;
use app\core\middlewares\EnsureTokenIsValid;
use app\core\Application;
use app\core\config\Config;
use app\core\config\PhpFileConfigLoader;
use app\core\EjecutionTime;
use app\core\exceptions\ConfigurationException;
use app\controllers\UsersController;
use app\helpers\RouteHelper;

EjecutionTime::init();
date_default_timezone_set('America/Bogota');
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

if (!defined('APP_DIRECTORY')) {
    define('APP_DIRECTORY', Path::getAppDirectory(Path::getBasePath(), PROJECT_ROOT));
}
if (!defined('APP_DIRECTORY_PATH')) {
    define('APP_DIRECTORY_PATH', APP_DIRECTORY == '/' ? '' : APP_DIRECTORY);
}

try {

    $configLoader = new PhpFileConfigLoader(PROJECT_ROOT . '/cfg.php');
    $config = Config::create($configLoader);
    $app = new Application(__DIR__, $config);
    $logger = Application::$app->logger;

    $app->route->group(function ($router) {
        $routes = RouteHelper::make();
        // Administrador
        $router->get('/dashboard-administrador', [ProyectosController::class, 'adminDashboardView']);
        // Listado de todos los clientes
        $router->get('/lista-de-clientes', [ClientesController::class, 'listaClientesView']);
        // Formulario para registrar un nuevo cliente
        $router->get('/registrar-cliente', [ClientesController::class, 'formularioRegistroClienteView']);
        // Accion de registrar un nuevo cliente
        $router->post('/registrar-cliente-action', [ClientesController::class, 'registroClienteAction']);

        // Formulario de registro de clientes de un evento
        $router->get('/registrar-cliente-evento/{proyecto}', [ClientesController::class, 'formularioAgregarClienteProyectoView'])->where(['proyecto' => "/^[0-9]+$/"]);
        // Accion de registrar un cliente en un evento
        $router->post('/registrar-cliente-a-proyecto-action', [ClientesController::class, 'registroClienteEventoAction']);

        // Eventos
        $router->get('/crear-proyecto', [ProyectosController::class, 'formularioCrearProyectoView']);
        $router->get('/eventos/evento/{evento_id}/editar', [ProyectosController::class, 'editarEventoView'])->where(['evento_id' => "/^[0-9]+$/"]);
        $router->get('/eventos/{evento_id}/servicios/asignar', [ProyectosController::class, 'asignarServiciosEventoView'])
            ->where(['evento_id' => "/^[0-9]+$/"]);
        $router->post('/eventos/evento/update', [ProyectosController::class, 'updateEventAction']);
        $router->get('/lista-de-eventos', [ProyectosController::class, 'eventosView']);
        $router->get('/lista-de-eventos/page/{page}', [ProyectosController::class, 'eventosView']);
        $router->post('/crear-proyecto-form', [ProyectosController::class, 'crearProyectoAction']);
        $router->post('/realizar-pago-efectivo', [ProyectosController::class, 'realizarPagoEfectivoAction']);
        $router->post('/sales/process', [ProyectosController::class, 'salesProcessAction']);
        $router->get('/evento/detalle/{proyecto}', [ProyectosController::class, 'eventoDetalleView'])->where(['proyecto' => "/^[0-9]+$/"]);
        $router->get('/evento/detalle/{proyecto}/page/{page}', [ProyectosController::class, 'eventoDetalleView'])->where(['proyecto' => "/^[0-9]+$/", 'page' => "/^[0-9]+$/"]);
        $router->get('/evento/pos/efectivo/{proyecto}', [ProyectosController::class, 'posEventoView'])->where(['proyecto' => "/^[0-9]+$/"]);
        $router->get('/eventos/{idEvento}/pagos/cliente/{idCliente}/efectivo', [ProyectosController::class, 'eventosPagoClienteEfectivoView'])->where([
            'idEvento' => "/^[0-9]+$/",
            'idCliente' => "/^[0-9]+$/"
        ]);
        $router->get('/imagenes/evento/{proyecto}/cliente/{id}', [ProyectosController::class, 'imagenesClienteView'])->where(['id' => "/^[0-9]+$/", 'proyecto' => "/^[0-9]+$/"]);
        $router->get('/evento/{proyecto}/cliente/{id}/cargar-imagenes', [ProyectosController::class, 'cargarImagenesClienteView'])->where(
            ['proyecto' => "/^[0-9]+$/", 'id' => "/^[0-9]+$/"]
        );
        $routes->getRoute($router, 'eventoClienteDetalleView');
        $routes->getRoute($router, 'eliminarImagenClienteAction');
        $router->post('/eventos/clientes/subir-imagenes', [ProyectosController::class, 'eventosClientesSubirImagenesAction']);

        // MODULO VENDEDORES EVENTO
        $router->get('/eventos/{evento_id}/vendedores/asignar', [ProyectosController::class, 'eventoVendedoresView'])
            ->where(['evento_id' => "/^[0-9]+$/"]);
        $router->post('/eventos/servicio/save', [ProyectosController::class, 'eventoServiciosSaveAction']);
        $router->post('/eventos/vendedores/save', [ProyectosController::class, 'eventoVendedoresSaveAction']);
        $router->post('/eventos/vendedor/delete', [ProyectosController::class, 'eventoVendedorDeleteAction']);

        // MODULO VENDEDORES
        $router->get('/vendedores', [VendedoresController::class, 'vendedoresView']);
        $router->get('/vendedores/registrar', [VendedoresController::class, 'vendedoresRegistrarView']);
        $router->post('/vendedores/registrar/save', [VendedoresController::class, 'vendedoresSaveAction']);
        $router->get('/vendedores/vendedor/{user_id}/update', [VendedoresController::class, 'vendedorUpdateView'])
            ->where(['user_id' => "/^[0-9]+$/"]);
        $router->post('/vendedores/vendedor/update', [VendedoresController::class, 'vendedorUpdateAction']);
        $router->get('/vendedores/vendedor/{user_id}/rendimiento', [VendedoresController::class, 'reporteRendimientoVendedorView'])
            ->where(['user_id' => "/^[0-9]+$/"]);
        $router->get('/vendedores/vendedor/{user_id}/rendimiento/page/{page}', [VendedoresController::class, 'reporteRendimientoVendedorView'])
            ->where(['user_id' => "/^[0-9]+$/", 'page' => "/^[0-9]+$/"]);

        // MODULO PORTAL DE VENTAS--------------
        $router->get('/dashboard-vendedor', [PortalVendedorController::class, 'salesPersonDashboardView']);
        $router->get('/portal/vendedor/eventos', [PortalVendedorController::class, 'seleccionarEventoView']);
        $router->get('/portal/vendedor/evento/{evento_id}/pos', [PortalVendedorController::class, 'posView'])
            ->where(['evento_id' => "/^[0-9]+$/"]);
        //--------------------------------------

        // Productos
        $router->get('/listado-de-productos', [ProductosController::class, 'productosView']);
        $router->get('/listado-de-productos/page/{page}', [ProductosController::class, 'productosView']);
        $router->get('/crear-producto', [ProductosController::class, 'crearProductoView']);
        $router->post('/crear-producto-form', [ProductosController::class, 'crearProductoAction']);
        $router->get('/editar-producto/{producto}', [ProductosController::class, 'editarProductoView'])->where(['producto' => "/^[0-9]+$/"]);
        $router->post('/editar-producto-form', [ProductosController::class, 'editarProductoAction']);
        $router->get('/producto/detalle/{producto}', [ProductosController::class, 'detalleView'])->where(['producto' => "/^[0-9]+$/"]);

        // Cliente
        $router->get('/dashboard-cliente', [CustomerController::class, 'dashboardCustomerView']);
        $router->get('/servicio/personalizado/{order_detail_id}', [CustomerController::class, 'servicioPersonalizadoView'])->where(['order_detail_id' => "/^[0-9]+$/"]);
        $router->get('/personalizar-servicio/{order_detail_id}', [CustomerController::class, 'imageSelectorAppView'])->where(['order_detail_id' => "/^[0-9]+$/"]);
        $router->post('/servicio/seleccionar-fotos', [CustomerController::class, 'selectServicePicturesAction']);
        $router->get('/servicios', [UIStoreController::class, 'listadoProductosView']);
        $router->get('/servicios', [CustomerController::class, 'serviciosView']);
        $router->post('/customers/search', [CustomerController::class, 'searchCustomerAction']);
        $router->post('/customers/new', [CustomerController::class, 'newCustomerAction']);

        $router->get('/orders', [OrdersController::class, 'ordersView']);
        $router->get('/orders/page/{page}', [OrdersController::class, 'ordersView']);
        //Sistema
        $router->post('/logout', [UsersController::class, 'logout']);
    }, [
        // 'prefix' => '/api',
        'middleware' => [EnsureTokenIsValid::class],
    ]);
    $app->route->get('/test-models', [TestController::class, 'test']);
    // $app->route->get('/test-conexion-google-drive', [TestController::class, 'testConexionGoogleDrive']);
    $app->route->get('/install-app', [FrameworkController::class, 'populateModels']);
    // $app->route->get('/generar-modelos', [FrameworkController::class, 'createModels']);
    // $app->route->get('/dashboard-administrador', [UIDemoController::class, 'uiDemoTplSneat']);
    $app->route->get('/', [UIAuthController::class, 'iniciarSesion']);
    $app->route->get('/iniciar-sesion', [UIAuthController::class, 'iniciarSesion']);
    $app->route->post('/autenticar', [AuthController::class, 'loginUser']);
    $app->run();
} catch (ConfigurationException $e) {
    $logger->log('error', $e->getMessage());
} catch (Exception $e) {
    $logger->log('error', $e->getMessage());
}
// $app->route->group(function ($router) {

//     $router->get('/', [UserController::class, 'index']);
//     $router->post('/logout', [UserController::class, 'logout']);
//     // $router->get('/test', [UserController::class, 'test']);
//     $router->get('/json/', [UserController::class, 'json']);
//     $router->get('/persona/{nombre}/edad/{edad}', [UserController::class, 'persona'])
//         ->where(['nombre' => "/^[a-zA-Z]+$/", 'edad' => "/^[0-9]+$/"])
//         ->middelwares([EnsureTokenIsValid::class]);
//     $router->get('/prueba2/', function () {
//         echo 'Hola';
//     });
// }, [
//     // 'prefix' => '/api',
//     'middleware' => [EnsureTokenIsValid::class],
// ]);



// EjecutionTime::show();
