<?php

namespace app\services\admin\proyectos\model;

use app\core\Application;
use app\core\database\builder\Builder;
use app\core\security\PasswordHashUtility;
use app\models\Employe;
use app\models\Project_salespeople;
use app\models\Role;
use app\models\User;

class EventoVendedoresService
{
    private Builder $builder;
    public function __construct()
    {
        $this->builder = Application::$app->builder;
    }

    public function eventoVendedorDelete(array $args = [], string $successUrl = '')
    {
        $result = $this->builder->transaction(function ($db) use ($args, $successUrl) {
            $model = new Project_salespeople();
            $delete = $model->findWhere(['users_id' => $args['user_id'], 'projects_id' => $args['event_id']]);
            if ($delete) {
                $result = $delete->remove();
                if ($result) {
                    return [
                        'status' => 'success',
                        'url' => $successUrl,
                        "message" => 'Vendedor eliminado correctamente.'
                    ];
                } else {
                    return [
                        'status' => 'fail',
                        'message' => 'No se puede eliminar al vendedor, compruebe los datos. Si el problema persiste, contacte al administrador.'
                    ];
                }
            } else {
                return [
                    'status' => 'fail',
                    'message' => 'No se puede eliminar al vendedor, compruebe los datos. Si el problema persiste, contacte al administrador.'
                ];
            }
        });

        return $result;
    }

    public function eventoVendedoresSave(array $args = [], string $successUrl = '')
    {
        $result = $this->builder->transaction(function ($db) use ($args, $successUrl) {

            foreach ($args['vendedores'] as $vendedor) {
                $model = new Project_salespeople();
                $repetido = $model->findWhere(['projects_id' => $args['event_id'], 'users_id' => $vendedor]);

                if ($repetido instanceof Project_salespeople) {
                    continue;
                } else {
                    $model = new Project_salespeople();
                    $model->projects_id = $args['event_id'];
                    $model->users_id = $vendedor;
                    $model->status = 'activo';
                    $result = $model->save();

                    if (!$result) {
                        return [
                            'status' => 'fail',
                            'message' => 'No se puede agregar al vendedor, compruebe los datos. Si el problema persiste, contacte al administrador.'
                        ];
                    }
                }
            }
            return [
                'status' => 'success',
                'url' => $successUrl,
                "message" => 'Vendedores agregados correctamente.'
            ];
        });
        return $result;
    }
}
