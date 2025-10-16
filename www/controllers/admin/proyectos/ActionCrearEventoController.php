<?php

namespace app\controllers\admin\proyectos;

// print errors
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use app\controllers\IRunnable;
use app\core\Application;
use app\helpers\DateHelper;
use app\helpers\NormalizeStringHelper;
use app\helpers\RouteHelper;
use app\services\ProjectsService;

class ActionCrearEventoController implements IRunnable
{
    private $response;
    public function __construct()
    {
        $this->response = Application::$app->response;
    }
    public function run(...$args)
    {
        $dataArgs = [
            'institution' => [],
            'location' => [],
            'project' => [],
        ];

        $institution_id = isset($args['institution_id']) && $args['institution_id'] !== 'crear' ? $args['institution_id'] : null;
        $location_id = isset($args['location_id']) && $args['location_id'] !== 'crear' ? $args['location_id'] : null;
        $nombre_evento = isset($args['nombre_evento']) ? $args['nombre_evento'] : null;
        $fecha_inicio = isset($args['fecha_inicio']) ? $args['fecha_inicio'] : null;
        $hora_ceremonia = isset($args['hora_ceremonia']) ? $args['hora_ceremonia'] : null;
        $fecha_fin = isset($args['fecha_fin']) ? $args['fecha_fin'] : DateHelper::sumarDias($fecha_inicio, 60);
        $descripcion = isset($args['descripcion']) ? $args['descripcion'] : null;
        $nombre_institucion = isset($args['nombre_institucion']) ? $args['nombre_institucion'] : null;
        $nombre_locacion = isset($args['nombre_locacion']) ? $args['nombre_locacion'] : null;
        $direccion = isset($args['direccion']) ? $args['direccion'] : null;
        // $pricing_plans_id = isset($args['pricing_plans']) ? $args['pricing_plans'] : null;
        $projectResult = null;

        if ($institution_id == null && $location_id == null) {
            // var_dump($args['nombre_institucion']);
            $dataArgs = [
                'institution' => [
                    'nombre' => NormalizeStringHelper::make()->normalize($nombre_institucion),
                ],
                'location' => [
                    'nombre' => NormalizeStringHelper::make()->normalize($nombre_locacion),
                    'direccion' => $direccion,
                ],
                'project' => [
                    'nombre_evento' => NormalizeStringHelper::make()->normalize($nombre_evento),
                    'fecha_inicio' => $fecha_inicio,
                    'hora_ceremonia' => $hora_ceremonia,
                    'fecha_fin' => $fecha_fin,
                    'descripcion' => $descripcion,
                    // 'pricing_plans_id' => $pricing_plans_id
                ],
            ];
            $projectResult = ProjectsService::make()->createProjectInstitutionLocation($dataArgs);
        } else if ($institution_id != null && $location_id == null) {
            $dataArgs = [
                'institution' => [
                    'institution_id' => $institution_id,
                ],
                'location' => [
                    'nombre' => NormalizeStringHelper::make()->normalize($nombre_locacion),
                    'direccion' => $direccion,
                ],
                'project' => [
                    'nombre_evento' => NormalizeStringHelper::make()->normalize($nombre_evento),
                    'fecha_inicio' => $fecha_inicio,
                    'hora_ceremonia' => $hora_ceremonia,
                    'fecha_fin' => $fecha_fin,
                    'descripcion' => $descripcion,
                    // 'pricing_plans_id' => $pricing_plans_id
                ],
            ];
            $projectResult = ProjectsService::make()->createProjectLocation($dataArgs);
        } else if ($institution_id == null && $location_id != null) {
            $dataArgs = [
                'institution' => [
                    'nombre' => NormalizeStringHelper::make()->normalize($nombre_institucion),
                ],
                'location' => [
                    'location_id' => $location_id
                ],
                'project' => [
                    'nombre_evento' => NormalizeStringHelper::make()->normalize($nombre_evento),
                    'fecha_inicio' => $fecha_inicio,
                    'hora_ceremonia' => $hora_ceremonia,
                    'fecha_fin' => $fecha_fin,
                    'descripcion' => $descripcion,
                    // 'pricing_plans_id' => $pricing_plans_id
                ],
            ];
            $projectResult = ProjectsService::make()->createProjectInstitution($dataArgs);
        } else if ($institution_id != null && $location_id != null) {
            $dataArgs = [
                'institution' => [
                    'institution_id' => $institution_id
                ],
                'location' => [
                    'location_id' => $location_id
                ],
                'project' => [
                    'nombre_evento' => NormalizeStringHelper::make()->normalize($nombre_evento),
                    'fecha_inicio' => $fecha_inicio,
                    'hora_ceremonia' => $hora_ceremonia,
                    'fecha_fin' => $fecha_fin,
                    'descripcion' => $descripcion,
                    // 'pricing_plans_id' => $pricing_plans_id
                ],
            ];
            $projectResult = ProjectsService::make()->createProject($dataArgs);
        }

        if ($projectResult) {
            $this->response->json([
                'status' => 'success',
                'url' => APP_DIRECTORY_PATH . '/lista-de-eventos',
                "message" => 'Proyecto creado correctamente'
            ])->send();
        } else {
            $this->response->json([
                'status' => 'fail',
                "message" => 'Oops Parece que hubo un error.'
            ])->send();
        }
    }
}
