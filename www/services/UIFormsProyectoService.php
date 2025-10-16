<?php

namespace app\services;

use app\core\Singleton;
use app\services\ui\form\FormBuilder;

class UIFormsProyectoService extends Singleton
{
    protected function __construct()
    {
        parent::__construct();
    }
    public function getCreateEventForm(array $args = [])
    {
        $formBuilder = new FormBuilder();
        return $formBuilder->addInput('nombre_evento', 'nombre_evento', 'text', 'Nombre del evento*')
            ->setPlaceholder('nombre_evento', 'Ingrese el nombre del evento')
            ->setAttribute('nombre_evento', 'required', true)

            ->addInput('fecha_inicio', 'fecha_inicio', 'date', 'Fecha inicio del proyecto*')
            ->setPlaceholder('fecha_inicio', 'Ingrese la fecha de inicio del proyecto')
            ->setAttribute('fecha_inicio', 'required', true)

            ->addInput('fecha_fin', 'fecha_fin', 'date', 'Fecha fin del proyecto*')
            ->setPlaceholder('fecha_fin', 'Ingrese la fecha de fin del proyecto')
            ->setAttribute('fecha_fin', 'required', true)

            ->addTextarea('descripcion', 'descripcion', 'Descripción del proyecto*')
            ->setPlaceholder('descripcion', 'Ingrese la descripción del proyecto')
            ->setAttribute('descripcion', 'required', true)

            ->addSelect('status', 'status', 'Estado del proyecto*', $args['status'])
            ->setPlaceholder('status', 'Seleccione el estado del proyecto')
            ->setAttribute('status', 'required', true)

            ->addSelect('institution_id', 'institution_id', 'Institución del proyecto*', $args['institutions'])
            ->setPlaceholder('institution_id', 'Seleccione la institución del proyecto')
            ->setAttribute('institution_id', 'required', true)

            // ->addSelect('pricing_plans', 'pricing_plans', 'Plan de precios*', $args['pricing_plans'])
            // ->setPlaceholder('pricing_plans', 'Seleccione el plan de precios')
            // ->setAttribute('pricing_plans', 'required', true)

            ->addInput('nombre_institucion', 'nombre_institucion', 'text', 'Nombre de la institución*')
            ->setPlaceholder('nombre_institucion', 'Ingrese el nombre de la institución')
            ->setAttribute('nombre_institucion', 'required', true)
            ->addCssClass('nombre_institucion', 'no_validate')

            ->addSelect('location_id', 'location_id', 'Ubicación del evento*', $args['locations'])
            ->setPlaceholder('location_id', 'Seleccione la ubicación del evento')
            ->setAttribute('location_id', 'required', true)

            ->addInput('nombre_locacion', 'nombre_locacion', 'text', 'Nombre de la ubicación del evento*')
            ->setPlaceholder('nombre_locacion', 'Ingrese el nombre de la ubicación del evento')
            ->setAttribute('nombre_locacion', 'required', true)
            ->addCssClass('nombre_locacion', 'no_validate')

            ->addInput('hora_ceremonia', 'hora_ceremonia', 'time', 'Hora de la ceremonia') // Campo agregado para la hora
            ->setPlaceholder('hora_ceremonia', 'Ingrese la hora de la ceremonia') // Placeholder opcional
            ->setAttribute('hora_ceremonia', 'required', true) // Atributo requerido

            ->addInput('direccion', 'direccion', 'text', 'Dirección del evento*')
            ->setPlaceholder('direccion', 'Ingrese la dirección del evento')
            ->setAttribute('direccion', 'required', true)
            ->addCssClass('direccion', 'no_validate')

            ->build();
    }
    public function editEventForm(array $args = [])
    {
        $formBuilder = new FormBuilder();
        return $formBuilder->addInput('nombre_evento', 'nombre_evento', 'text', 'Nombre del evento*')
            ->setPlaceholder('nombre_evento', 'Ingrese el nombre del evento')
            ->setAttribute('nombre_evento', 'required', true)
            ->setValue('nombre_evento', $args['current_event_name'])

            ->addInput('evento_id', 'evento_id', 'hidden', 'ID del evento')
            ->setValue('evento_id', $args['current_event_id'])

            ->addInput('fecha_inicio', 'fecha_inicio', 'date', 'Fecha inicio del proyecto*')
            ->setPlaceholder('fecha_inicio', 'Ingrese la fecha de inicio del proyecto')
            ->setAttribute('fecha_inicio', 'required', true)
            ->setValue('fecha_inicio', $args['current_start_date'])

            ->addInput('fecha_fin', 'fecha_fin', 'date', 'Fecha fin del proyecto*')
            ->setPlaceholder('fecha_fin', 'Ingrese la fecha de fin del proyecto')
            ->setAttribute('fecha_fin', 'required', true)
            ->setValue('fecha_fin', $args['current_end_date'])

            ->addInput('hora_ceremonia', 'hora_ceremonia', 'time', 'Hora de la ceremonia')
            ->setPlaceholder('hora_ceremonia', 'Ingrese la hora de la ceremonia')
            ->setAttribute('hora_ceremonia', 'required', true)
            ->setValue('hora_ceremonia', $args['current_ceremony_time'])

            ->addTextarea('descripcion', 'descripcion', 'Descripción del proyecto*')
            ->setPlaceholder('descripcion', 'Ingrese la descripción del proyecto')
            ->setAttribute('descripcion', 'required', true)
            ->setValue('descripcion', $args['current_description'])

            // ->addSelect('pricing_plans', 'pricing_plans', 'Plan de precios*', $args['pricing_plans_options'])
            // ->setPlaceholder('pricing_plans', 'Seleccione el plan de precios')
            // ->setAttribute('pricing_plans', 'required', true)
            // ->setValue('pricing_plans', $args['current_pricing_plan'])


            ->addSelect('status', 'status', 'Estado del proyecto*', $args['event_status_options'])
            ->setPlaceholder('status', 'Seleccione el estado del proyecto')
            ->setAttribute('status', 'required', true)
            ->setValue('status', $args['current_event_status'])

            ->addSelect('institution_id', 'institution_id', 'Institución del proyecto*', $args['institutions_options'])
            ->setPlaceholder('institution_id', 'Seleccione la institución del proyecto')
            ->setAttribute('institution_id', 'required', true)
            ->setValue('institution_id', $args['current_institution_id'])

            ->addInput('nombre_institucion', 'nombre_institucion', 'text', 'Nombre de la institución*')
            ->setPlaceholder('nombre_institucion', 'Ingrese el nombre de la institución')
            ->setAttribute('nombre_institucion', 'required', true)
            ->addCssClass('nombre_institucion', 'no_validate')

            ->addSelect('location_id', 'location_id', 'Ubicación del evento*', $args['locations_options'])
            ->setPlaceholder('location_id', 'Seleccione la ubicación del evento')
            ->setAttribute('location_id', 'required', true)
            ->setValue('location_id', $args['current_location_id'])

            ->addInput('nombre_locacion', 'nombre_locacion', 'text', 'Nombre de la ubicación del evento*')
            ->setPlaceholder('nombre_locacion', 'Ingrese el nombre de la ubicación del evento')
            ->setAttribute('nombre_locacion', 'required', true)
            ->addCssClass('nombre_locacion', 'no_validate')

            ->addInput('direccion', 'direccion', 'text', 'Dirección del evento*')
            ->setPlaceholder('direccion', 'Ingrese la dirección del evento')
            ->setAttribute('direccion', 'required', true)
            ->addCssClass('direccion', 'no_validate')
            ->setValue('direccion', $args['current_event_address'])

            ->build();
    }
}
