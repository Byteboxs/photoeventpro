<?php

namespace app\services\ui\table;


class CitaControlDataStrategy implements ControlDataStrategy
{

    public function generateControlData(array $item): string
    {
        $id = $item['cita_id'];
        $estado = $item['estado'];

        $data = '<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group me-2" role="group" aria-label="First group">';
        $data .= match ($estado) {
            Cita::$CITA_PENDIENTE => '
                        <button data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Confirmar cita" type="button" class="btn btn-outline-secondary" name="confirmar" id="' . $id . '"><i class="fas fa-calendar-check"></i></button>
                        <button data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Modificar cita" type="button" class="btn btn-outline-secondary" name="modificar" id="' . $id . '"><i class="far fa-calendar-alt"></i></button>
                        <button data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Cancelar cita" type="button" class="btn btn-danger" name="cancelar" id="' . $id . '"><i class="far fa-calendar-times"></i></button>',
            Cita::$CITA_CONFIRMADA => '
                        <button data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Modificar cita" type="button" class="btn btn-outline-secondary" name="modificar" id="' . $id . '"><i class="far fa-calendar-alt"></i></button>
                        <button data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Cancelar cita" type="button" class="btn btn-outline-danger" name="cancelar" id="' . $id . '"><i class="far fa-calendar-times"></i></button>',
            Cita::$CITA_CANCELADA => '<span class="badge text-bg-danger">' . Cita::$CITA_CANCELADA . '</span>',
            Cita::$CITA_TERMINADA => '<span class="badge text-bg-success">' . Cita::$CITA_TERMINADA . '</span>',
        };

        $data .= '</div>
                </div>';
        return $data;
    }
    public function generateVeterinarioControlData(array $item): string
    {
        $id = $item['cita_id'];
        $estado = $item['estado'];

        $data = '<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group me-2" role="group" aria-label="First group">';
        $data .= match ($estado) {
            Cita::$CITA_CONFIRMADA => '
            <a class="btn btn-outline-secondary" href="' . APP_DIRECTORY_PATH . '/consulta/' . $id . '" data-bs-toggle="tooltip" data-bs-placement="left"
                  data-bs-custom-class="custom-tooltip"
                  data-bs-title="Ver consulta"><i class="fas fa-stethoscope"></i>
            </a>',
            Cita::$CITA_CANCELADA => '<span class="badge text-bg-danger">' . Cita::$CITA_CANCELADA . '</span>',
            Cita::$CITA_TERMINADA => '<span class="badge text-bg-success">' . Cita::$CITA_TERMINADA . '</span>',
        };

        $data .= '</div>
                </div>';
        return $data;
    }

    public function modifyInfoColumn(array $item): string
    {
        $estado = $item['estado'];
        return match ($estado) {
            Cita::$CITA_CONFIRMADA => '<span class="badge text-bg-primary">' . Cita::$CITA_CONFIRMADA . '</span>',
            Cita::$CITA_CANCELADA => '<span class="badge text-bg-danger">' . Cita::$CITA_CANCELADA . '</span>',
            Cita::$CITA_TERMINADA => '<span class="badge text-bg-success">' . Cita::$CITA_TERMINADA . '</span>',
            default => '<span class="badge text-bg-secondary">' . Cita::$CITA_PENDIENTE . '</span>',
        };
    }
}
