<?php

namespace app\services;

use app\core\Application;
use app\core\security\PasswordHashUtility;
use app\core\Singleton;
use app\helpers\RouteHelper;
use app\models\Billing_information;
use app\models\Customer;
use app\models\Customers_event;
use app\models\Document_type;
use app\models\Employe;
use app\models\Person;
use app\models\Project;
use app\models\Role;
use app\models\Shipping_information;
use app\models\User;

class UsersService extends Singleton
{
    private $builder;
    protected function __construct()
    {
        parent::__construct();
        $this->builder = Application::$app->builder;
    }
    public function createEmpleado(array $args)
    {
        $result = $this->builder->transaction(function ($db) use ($args) {
            $modelRole = new Role();
            $role = $modelRole->findWhere(['name' => $args['role']]);

            $documentModel = new Document_type();
            $documento = $documentModel->findWhere(['nombre' => $args['document_type']]);

            $user = new User();
            $user->roles_id = $role->id;
            $user->document_type_id = $documento->id;
            $user->email = $args['email'];
            $user->password_hash = PasswordHashUtility::create()->hashPassword($args['password']);
            $user->primer_nombre = $args['primer_nombre'];
            $user->segundo_nombre = $args['segundo_nombre'];
            $user->primer_apellido = $args['primer_apellido'];
            $user->segundo_apellido = $args['segundo_apellido'];
            $user->direccion = $args['direccion'];
            $user->telefono = $args['telefono'];
            $user->numero_identificacion = $args['numero_identificacion'];
            $user->status = User::ACTIVO;
            $user = $user->save();

            $employe = new Employe();
            $employe->user_id = $user->id;
            $employe->cargo = $args['cargo'];
            $employe->save();

            return $user;
        });
        return $result;
    }
    public function createClienteProyecto(array $args)
    {
        $result = $this->builder->transaction(function ($db) use ($args) {
            // var_dump($args);

            $userModel = new User();
            $userEmail = $userModel->findWhere([
                'email' => $args['email'],
            ]);

            $userNumero = $userModel->findWhere([
                'numero_identificacion' => $args['numero_identificacion'],
            ]);

            if ($userEmail != null || $userNumero != null) {
                return [
                    'status' => 'fail',
                    "message" => 'El cliente ya existe.'
                ];
            }

            $user = new User();
            $user->roles_id = $args['roles_id'];
            $user->document_type_id = $args['document_type_id'];
            $user->email = $args['email'];
            $user->password_hash = PasswordHashUtility::create()->hashPassword($args['numero_identificacion']);
            $user->primer_nombre = $args['primer_nombre'];
            if ($args['segundo_nombre'] != '') {
                $user->segundo_nombre = $args['segundo_nombre'];
            }
            $user->primer_apellido = $args['primer_apellido'];
            if ($args['segundo_apellido'] != '') {
                $user->segundo_apellido = $args['segundo_apellido'];
            }
            $user->direccion = $args['direccion'];
            $user->telefono = $args['telefono'];
            $user->numero_identificacion = $args['numero_identificacion'];
            $user->status = User::ACTIVO;
            $user = $user->save();

            if (!$user) {
                return [
                    'status' => 'fail',
                    "message" => 'No se puede crear el usuario.'
                ];
            }

            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer = $customer->save();

            if (!$customer) {
                return [
                    'status' => 'fail',
                    "message" => 'No se puede crear el cliente.'
                ];
            }

            $customersEvents = new Customers_event();
            $customersEvents->project_id = $args['project_id'];
            $customersEvents->customer_id = $customer->id;
            $customersEvents = $customersEvents->save();

            if (!$customersEvents) {
                return [
                    'status' => 'fail',
                    "message" => 'No se puede agregar al cliente al evento.'
                ];
            }

            $project = new Project();
            $project = $project->find($args['project_id']);
            $project->status = "activo";
            $project = $project->save();

            if (!$project) {
                return [
                    'status' => 'fail',
                    "message" => 'No se puede activar el proyecto.'
                ];
            }

            $cedulaCiudadania = 1;
            $tipoPersona = "natural";
            $razonSocial = $args['nombre_contacto'];
            $nit = $args['numero_identificacion'];

            $billingInformation = new Billing_information();
            $billingInformation->customer_id = $customer->id;
            $billingInformation->document_type_id = $cedulaCiudadania;
            $billingInformation->tipo_persona = $tipoPersona;
            $billingInformation->razon_social = $razonSocial;
            $billingInformation->nit = $nit;
            $billingInformation->direccion_facturacion = $args['direccion_envio'];
            $billingInformation->status = 'activo';

            $billingInformation = $billingInformation->save();

            if (!$billingInformation) {
                return [
                    'status' => 'fail',
                    "message" => 'No se puede crear la información de facturación.'
                ];
            }

            $shippingInformation = new Shipping_information();
            $shippingInformation->customer_id = $customer->id;
            $shippingInformation->nombre_contacto = $args['nombre_contacto'];
            $shippingInformation->direccion_envio = $args['direccion_envio'];
            $shippingInformation->telefono_contacto = $args['telefono_contacto'];
            $shippingInformation = $shippingInformation->save();

            if (!$shippingInformation) {
                return [
                    'status' => 'fail',
                    "message" => 'No se puede crear la información de envío.'
                ];
            }

            return [
                'status' => 'success',
                // 'url' => APP_DIRECTORY_PATH . '/evento/detalle/' . $args['project_id'],
                'url' => APP_DIRECTORY_PATH . '/eventos/' . $args['project_id'] . '/pagos/cliente/' . $customer->id . '/efectivo',
                "message" => 'Se registro el cliente al evento.',
                'customer' => $user,
            ];
        });
        if (!$result) {
            return [
                'status' => 'fail',
                "message" => 'No se puede crear el cliente.'
            ];
        }
        return $result;
    }
}
