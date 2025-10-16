<?php

namespace app\controllers\admin\clientes;

use app\controllers\IRunnable;
use app\core\Application;
use app\helpers\RouteHelper;
use app\models\Role;
use app\services\UsersService;

class ActionRegistrarClienteEventoController implements IRunnable
{
    private $response;
    private $request;
    private $data;
    private $rolCliente;
    public function __construct($request)
    {
        $this->request = $request;
        $this->response = Application::$app->response;
        $this->data = $this->request["request"]->getData();
        $modelRole = new Role();
        $this->rolCliente = $modelRole->findWhere(['name' => 'Cliente']);
        $this->data['roles_id'] = $this->rolCliente->id;
    }

    private function save($formData)
    {
        return UsersService::make()->createClienteProyecto($formData);
    }

    public function run(...$args)
    {
        if (count($args) > 0 && isset($args[0]) && is_string($args[0]) && $args[0] == 'vendedor') {
            $result = $this->save($this->data);
            if ($result != null && $result['status'] == 'success') {
                $customer = $result['customer'];
                $result = [
                    'success' => true,
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->primer_nombre . ' ' . $customer->primer_apellido,
                        'email' => $customer->email,
                    ],
                    'message' => 'Se encontro al cliente: ' . $customer->primer_nombre . ' ' . $customer->primer_apellido,
                ];
            } else {
                $result = [
                    'success' => false,
                    'customer' => null,
                    'message' => 'Error: ' . $result['message']
                ];
            }
            $this->response->json($result)->send();
            exit();
        }
        $modelRole = new Role();
        $role = $modelRole->findWhere(['name' => 'Cliente']);
        $this->data['roles_id'] = $role->id;
        $result = UsersService::make()->createClienteProyecto($this->data);
        if ($result) {
            $this->response->json($result)->send();
        } else {
            $this->response->json([
                'status' => 'fail',
                "message" => 'Se produjo un error al registrar el cliente, es probable que ya exista un cliente con el mismo correo electrónico'
            ])->send();
        }
    }
}
