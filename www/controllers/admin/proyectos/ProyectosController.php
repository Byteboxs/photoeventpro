<?php

namespace app\controllers\admin\proyectos;

use app\controllers\admin\dashboard\UIAdminDashboardController;
use app\controllers\portal_vendedor\UIPos;
use app\core\Application;
use app\core\http\Request;
use app\services\ProjectsService;

class ProyectosController
{
    public function __construct() {}

    public function formularioCrearProyectoView(Request $request)
    {
        (new UIFormCrearEventoController())->draw($request);
    }

    public function editarEventoView(...$request)
    {
        (new UIFormEditarEventoController(...$request))->draw();
    }
    public function asignarServiciosEventoView(...$request)
    {
        (new AsignarServiciosEventoViewController(...$request))->draw();
    }

    public function updateEventAction(...$request)
    {
        (new UpdateEventAction(...$request))->run();
    }

    public function eventosView(...$request)
    {
        (new UIEventosController())->draw(...$request);
    }

    public function crearProyectoAction(...$args)
    {
        (new ActionCrearEventoController())->run(...$args);
    }

    public function eventoDetalleView(...$args)
    {
        (new UIDetalleProyectoController())->draw(...$args);
    }

    public function eventoClienteDetalleView(...$args)
    {
        (new UIEventoClienteDetalleController())->draw(...$args);
    }

    public function eliminarImagenClienteAction(...$args)
    {
        (new ActionEliminarImagenClienteController())->run(...$args);
    }

    public function posEventoView(...$args)
    {
        (new UIPosEventoController())->draw(...$args);
    }
    public function realizarPagoEfectivoAction(...$args)
    {
        $salesperson_id = $args['request']->session()->get('userId');
        $data = [
            'client_id' => $args['client'],
            'project_id' => $args['project'],
            'salesperson_id' => $salesperson_id,
            'items' => $args['items']
        ];
        (new ActionRealizarPagoEfectivoController())->run(...$data);
    }
    public function eventosPagoClienteEfectivoView(...$args)
    {
        (new UIEventosPagoClienteEfectivoController())->draw(...$args);
    }

    public function cargarImagenesClienteView(...$args)
    {
        (new UICargarImagenesClienteController())->draw(...$args);
    }

    public function eventosClientesSubirImagenesAction(...$args)
    {
        (new ActionSubirImagenesClientesController())->run(...$args);
    }

    public function adminDashboardView(...$args)
    {
        (new UIAdminDashboardController())->draw(...$args);
    }

    public function eventoVendedoresView(...$args)
    {
        (new UIEventoVendedores(...$args))->draw();
    }

    public function eventoVendedoresSaveAction(...$args)
    {
        (new ActionEventoVendedoresSave(...$args))->run();
    }
    public function eventoServiciosSaveAction(...$args)
    {
        // (new ActionEventoVendedoresSave(...$args))->run();
        $evento_id = $args['event_id'];
        $services = $args['services_ids'] ?? [];
        $services_prices = $args['services_prices'] ?? [];
        $proccessed_services = [];

        for ($i = 0; $i < count($services); $i++) {
            $service_id = $services[$i];
            $service_price = $services_prices[$i] ?? 0;
            $proccessed_services[] = [
                'event_id' => $evento_id,
                'service_id' => $service_id,
                'price' => $service_price
            ];
        }

        echo '<pre>Procesando servicios asignados al evento: ' . $evento_id . PHP_EOL;
        var_dump($proccessed_services);
        echo '</pre>';
    }

    public function eventoVendedorDeleteAction(...$args)
    {
        (new ActionEventoVendedorDelete(...$args))->run();
    }

    public function salesProcessAction(...$args)
    {
        $appResponse = Application::$app->response;
        $items = $args['items'] ?? [];
        $payments = $args['payments'] ?? [];
        $data = $args['request']->getData();
        unset($data['items']);
        unset($data['payments']);
        $data['items'] = $items;
        $data['payments'] = $payments;

        $result = ProjectsService::make()->salesProcess($data);
        if ($result['status'] == 'fail') {
            $response = [
                'success' => false,
                'message' => $result['message'],
            ];
        } else {
            $response = [
                'success' => true,
                'message' => $result['message'],
            ];
        }
        // var_dump($response);
        $appResponse->json($response)->send();
        // var_dump($result);
        // var_dump($data['payments']);
        // var_dump($args);
    }
}
