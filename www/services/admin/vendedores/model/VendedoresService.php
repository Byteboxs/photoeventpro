<?php

namespace app\services\admin\vendedores\model;

use app\core\Application;
use app\core\database\builder\Builder;
use app\core\security\PasswordHashUtility;
use app\core\Singleton;
use app\models\Document_type;
use app\models\Employe;
use app\models\Role;
use app\models\User;

class VendedoresService
{
    private Builder $builder;
    public function __construct()
    {
        $this->builder = Application::$app->builder;
    }

    public function registrarVendedor(array $args = [], string $successUrl = '')
    {
        $result = $this->builder->transaction(function ($db) use ($args, $successUrl) {
            $modelRole = new Role();
            $role = $modelRole->findWhere(['name' => 'Vendedor']);

            if (!$role) {
                return [
                    'status' => 'fail',
                    'message' => 'No se encontro el rol vendedor, por favor verifique los parametros iniciales en la aplicacion.'
                ];
            }

            $user = new User();
            $user->roles_id = $role->id;
            $user->document_type_id = $args['document_type_id'];
            $user->email = $args['email'];
            $user->password_hash = PasswordHashUtility::create()->hashPassword($args['numero_identificacion']);
            $user->primer_nombre = $args['primer_nombre'];
            $user->segundo_nombre = $args['segundo_nombre'];
            $user->primer_apellido = $args['primer_apellido'];
            $user->segundo_apellido = $args['segundo_apellido'];
            $user->direccion = $args['direccion'];
            $user->telefono = $args['telefono'];
            $user->numero_identificacion = $args['numero_identificacion'];
            $user->status = User::ACTIVO;
            $user = $user->save();

            if (!$user) {
                return [
                    'status' => 'fail',
                    'message' => 'No se puede agregar al vendedor, compruebe los datos, si los datos son correctos, vuelva a intentarlo.'
                ];
            }

            $employe = new Employe();
            $employe->user_id = $user->id;
            $employe->cargo = 'Vendedor';
            $employe = $employe->save();

            if (!$employe) {
                return [
                    'status' => 'fail',
                    'message' => 'Ocurrio un error inesperado, por favor vuelva a intentarlo. Si persiste el problema, comuníquese con el administrador.'
                ];
            }

            return [
                'status' => 'success',
                'url' => $successUrl,
                "message" => 'Vendedor registrado correctamente.'
            ];
        });
        return $result;
    }
    public function editarVendedor(array $args = [], string $successUrl = '')
    {
        $result = $this->builder->transaction(function ($db) use ($args, $successUrl) {
            $user = new User();
            $user = $user->find($args['user_id']);
            // $user->roles_id = $role->id;
            $user->document_type_id = $args['document_type_id'];
            $user->email = $args['email'];
            $user->password_hash = PasswordHashUtility::create()->hashPassword($args['numero_identificacion']);
            $user->primer_nombre = $args['primer_nombre'];
            $user->segundo_nombre = $args['segundo_nombre'];
            $user->primer_apellido = $args['primer_apellido'];
            $user->segundo_apellido = $args['segundo_apellido'];
            $user->direccion = $args['direccion'];
            $user->telefono = $args['telefono'];
            $user->numero_identificacion = $args['numero_identificacion'];
            $user->status = $args['status'];
            $user = $user->save();

            if (!$user) {
                return [
                    'status' => 'fail',
                    'message' => 'No se puede editar al vendedor, compruebe el email y/o el número de identificación.'
                ];
            }

            return [
                'status' => 'success',
                'url' => $successUrl,
                "message" => 'Vendedor editado correctamente.'
            ];
        });
        return $result;
    }
    public function eliminarVendedor(User $user, array $args = []) {}
}
