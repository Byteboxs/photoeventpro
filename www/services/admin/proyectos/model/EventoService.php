<?php

namespace app\services\admin\proyectos\model;

use app\core\Application;
use app\core\database\builder\Builder;
use app\helpers\DateHelper;
use app\helpers\NormalizeStringHelper;
use app\models\Institution;
use app\models\Location;
use app\models\Project;

class EventoService
{
    private Builder $builder;
    private $evento;
    private $institution;
    private $location;
    public function __construct()
    {
        $this->builder = Application::$app->builder;
    }

    private function createInstitution($name)
    {
        $name = NormalizeStringHelper::make()->normalize($name);
        $institution = new Institution();
        $institution->nombre = $name;
        $institution = $institution->findOrCreate('nombre');
        return $institution;
    }

    private function findInstitution($institutionId)
    {
        $institution = new Institution();
        $institution = $institution->find($institutionId);
        if (!$institution) {
            return null;
        }
        return $institution;
    }

    private function findOrCreateInstitution($args)
    {
        if (isset($args['institution_id']) && $args['institution_id'] == 'crear') {
            return $this->createInstitution($args['nombre_institucion']);
        } else {
            return $this->findInstitution($args['institution_id']);
        }
    }

    private function createLocation($name, $address)
    {
        $name = NormalizeStringHelper::make()->normalize($name);
        $location = new Location();
        $location->nombre = $name;
        $location->direccion = $address;
        $location = $location->findOrCreate('nombre');
        return $location;
    }

    private function findLocation($locationId)
    {
        $location = new Location();
        $location = $location->find($locationId);
        if (!$location) {
            return null;
        }
        return $location;
    }

    private function findOrCreateLocation($args)
    {
        if (isset($args['location_id']) && $args['location_id'] == 'crear') {
            return $this->createLocation($args['nombre_locacion'], $args['direccion']);
        } else {
            return $this->findLocation($args['location_id']);
        }
    }

    private function findActiveEvent($eventoId)
    {
        $evento = new Project();
        $evento = $evento->findWhere(['id' => $eventoId, 'status' => 'activo']);
        if (!$evento) {
            return null;
        }
        return $evento;
    }

    private function updateEventData($evento, $institution, $location, $args)
    {
        $evento->institution_id = $institution->id;
        $evento->pricing_plans_id = $args['pricing_plans'];
        $evento->location_id = $location->id;
        $evento->nombre_evento = NormalizeStringHelper::make()->normalize($args['nombre_evento']);
        $evento->fecha_inicio = $args['fecha_inicio'];
        $evento->fecha_fin = DateHelper::sumarDias($args['fecha_inicio'], 60);
        $evento->status = $args['status'];
        $evento->hora_ceremonia = $args['hora_ceremonia'];
        $evento->descripcion = $args['descripcion'] ?? '';
        $evento = $evento->save();

        return $evento;
    }




    public function updateEvent(array $args = [], string $successUrl = '')
    {
        $result = $this->builder->transaction(function ($db) use ($args, $successUrl) {
            $eventoId = $args['evento_id'] ?? null;
            if (!$eventoId) {
                return [
                    'status' => 'fail',
                    'message' => 'El ID del evento es requerido.'
                ];
            }

            $this->evento = $this->findActiveEvent($eventoId);
            if (!$this->evento) {
                return [
                    'status' => 'fail',
                    'message' => 'El evento no se puede editar o no existe.'
                ];
            }

            $this->institution = $this->findOrCreateInstitution($args);
            if (!$this->institution) {
                return [
                    'status' => 'fail',
                    'message' => 'No se pudo encontrar o crear la institución.'
                ];
            }

            $this->location = $this->findOrCreateLocation($args);
            if (!$this->location) {
                return [
                    'status' => 'fail',
                    'message' => 'No se pudo encontrar o crear la locación.'
                ];
            }

            $this->evento = $this->updateEventData($this->evento, $this->institution, $this->location, $args);
            if (!$this->evento) {
                return [
                    'status' => 'fail',
                    'message' => 'No se pudo actualizar el evento. Verifique los datos.'
                ];
            }

            return [
                'status' => 'success',
                'url' => $successUrl,
                "message" => 'Evento actualizado correctamente.'
            ];
        });

        return $result;
    }
}
