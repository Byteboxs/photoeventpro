<?php

namespace app\controllers\customer;

use app\controllers\admin\clientes\ActionRegistrarClienteEventoController;
use app\controllers\customer\dashboard\UIClienteDashboardController;
use app\controllers\customer\fotoalbum\UICrearFotoAlbumController;
use app\controllers\customer\store\UIServiciosController;
use app\core\database\builder\Criteria;
use app\core\http\Request;
use app\core\views\View;
use app\models\Customer;

class CustomerController
{
    public function __construct() {}

    public function imageSelectorAppView(...$args)
    {
        (new UICrearFotoAlbumController())->draw(...$args);
    }
    public function servicioPersonalizadoView(...$args)
    {
        try {
            (new UIServicioPersonalizadoController())->draw(...$args);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    public function selectServicePicturesAction(...$args)
    {
        (new ActionSelectServicePicturesController())->run(...$args);
    }
    public function serviciosView(...$args)
    {
        (new UIServiciosController())->draw(...$args);
    }
    public function dashboardCustomerView(...$args)
    {
        (new UIClienteDashboardController())->draw(...$args);
    }

    public function searchCustomerAction(...$args)
    {
        $searchTerm = $args['searchTerm'] ?? '';
        $result = [
            'success' => false,
            'message' => 'Cliente no encontrado.',
        ];
        $customer = null;
        try {
            if (is_numeric($searchTerm)) {
                $criteria = Criteria::equals('u.numero_identificacion', $searchTerm);
                $customer = Customer::findCustomerBy($criteria);
            } else if (is_string($searchTerm)) {
                if (filter_var($searchTerm, FILTER_VALIDATE_EMAIL)) {
                    $criteria = Criteria::equals('u.email', $searchTerm);
                } else {
                    $searchTerm = htmlspecialchars($searchTerm);
                    $criteria = Criteria::like("CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido,
                    IFNULL(CONCAT(' ', u.segundo_apellido), ''))", "%{$searchTerm}%", false);
                }
                $customer = Customer::findCustomerBy($criteria);
            }
            if ($customer != null) {
                $result = [
                    'success' => true,
                    'customer' => [
                        'id' => $customer->user_id,
                        'name' => $customer->nombre_completo,
                        'email' => $customer->email,
                    ],
                    'message' => 'Cliente: ' . $customer->nombre_completo,
                ];
            }
        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
        echo json_encode($result);
        exit;
    }

    public function newCustomerAction(...$args)
    {
        (new ActionRegistrarClienteEventoController($args))->run('vendedor');
        // var_dump($args['request']->getData());
    }
}
