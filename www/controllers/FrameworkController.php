<?php

namespace app\controllers;

use app\core\Application;
use app\core\database\builder\Builder;
use app\core\database\builder\MySQLQueryBuilder;
use app\core\database\builder\RepositoryFactory;
use app\core\database\connection\ConnectionPool;
use app\core\database\SchemaToModelGenerator;
use app\core\exceptions\InvalidBuilderException;
use app\core\exceptions\ModelNotFoundException;
use app\core\exceptions\ModelValidationException;
use app\core\exceptions\RepositoryNotFoundException;
use app\core\http\Request;
use app\core\http\Response;
use app\core\model\Model;
use app\core\security\PasswordHashUtility;
use app\models\Categoria;
use app\models\Document_type;
use app\models\Employe;
use app\models\Person;
use app\models\Role;
use app\models\User;
use app\models\Usuario;
use app\models\UsuariosRepository;
use app\services\UsersService;

class FrameworkController
{
    private $response;
    private $logger;
    private $builder;
    private $baseController;
    public function __construct()
    {
        // Response::json(
        //     ['key' => 'value'],
        // )->send();
        $this->logger = Application::$app->logger;
        $this->response = Application::$app->response;
        $this->builder = Application::$app->builder;
        $this->baseController = new BaseController();
    }

    public function createModels(Request $request): void
    {
        try {
            $db = Application::$app->db;
            $outputPath = PROJECT_ROOT . '/models/';
            $generator = new SchemaToModelGenerator($db, $outputPath);
            $generator->generate();
            $this->response->status(Response::HTTP_OK);
            $this->response->content("Modelos generados exitosamente en: $outputPath");
            $this->response->send();
        } catch (\Throwable $th) {
            $this->baseController->handleError($th);
        }
    }
    public function populateModels(Request $request): void
    {
        // $pricing_plans = [
        //     ['name' => 'Plan Basico', 'price' => 0],
        //     ['name' => 'Plan Avanzado', 'price' => 50],
        //     ['name' => 'Plan Premium', 'price' => 80],
        // ];

        // try {
        //     foreach ($pricing_plans as $plan) {
        //         $model = new \app\models\Pricing_plan();
        //         $model->name = $plan['name'];
        //         $model->price = $plan['price'];
        //         $model->save();
        //     }
        // } catch (\Throwable $th) {
        //     echo 'Error al crear planes de precios: ' . $th->getMessage() . '<br>';
        // }

        $categorias = [
            ['nombre' => 'Impresion', 'descripcion' => 'Impresion de documentos'],
            ['nombre' => 'Digital', 'descripcion' => 'Envio de imagenes digitales'],
        ];

        try {
            foreach ($categorias as $cat) {
                $model = new Categoria();
                $model->nombre = $cat['nombre'];
                $model->descripcion = $cat['descripcion'];
                $model->save();
            }
        } catch (\Throwable $th) {
            echo 'Error al crear categorias: ' . $th->getMessage() . '<br>';
        }

        $tiposDocumento = [
            ['name' => 'Cédula de ciudadanía', 'code' => 'CC'],
            ['name' => 'Cédula de extranjería', 'code' => 'CE'],
            ['name' => 'Tarjeta de identidad', 'code' => 'TI'],
            ['name' => 'Pasaporte', 'code' => 'PA'],
            ['name' => 'Número de Identificación Tributaria', 'code' => 'NIT'],
        ];

        try {
            foreach ($tiposDocumento as $doc) {
                $modelDoc = new Document_type();
                $modelDoc->nombre = $doc['name'];
                $modelDoc->codigo = $doc['code'];
                $modelDoc->save();
            }
        } catch (\Throwable $th) {
            echo 'Error al crear tipos de documento: ' . $th->getMessage() . '<br>';
        }

        $roles = [
            [
                'name' => 'Administrador',
                'description' => 'Administrador del sistema',
            ],
            [
                'name' => 'Vendedor',
                'description' => 'Vendedor de productos',
            ],
            [
                'name' => 'Cliente',
                'description' => 'Cliente final',
            ]
        ];

        try {
            foreach ($roles as $rol) {
                $modelRole = new Role();
                $modelRole->name = $rol['name'];
                $modelRole->description = $rol['description'];
                $modelRole->save();
            }
        } catch (\Throwable $th) {
            echo 'Error al crear roles: ' . $th->getMessage() . '<br>';
        }

        $empleadoArgs = [
            'role' => 'Administrador',
            'document_type' => 'Cédula de ciudadanía',
            'email' => 'administrador@example.com',
            'password' => '123456',
            'primer_nombre' => 'Administrador',
            'segundo_nombre' => 'Fulano',
            'primer_apellido' => 'Casas',
            'segundo_apellido' => 'Perez',
            'direccion' => 'Calle 22 # 99a-77',
            'telefono' => '123456789',
            'numero_identificacion' => '123456789',
            'cargo' => 'Administrador',
        ];

        $empleadoResult = UsersService::make()->createEmpleado($empleadoArgs);

        $msg = "Si se pudo poblar la base de datos";

        $this->response->status(Response::HTTP_OK);
        $this->response->content($msg);
        $this->response->send();
    }
}
