<?php

namespace app\services\ui\table;

use app\core\security\DataEnciptionUtility;

class AnimalControlDataStrategy implements ControlDataStrategy
{

    public function generateControlData(array $item): string
    {
        $id = $item['animal_id'];
        $data = '
        <div class="btn-group">
            <a class="btn btn-outline-secondary" href="' . APP_DIRECTORY_PATH . '/agendarPrimeraCita/' . $id . '" data-bs-toggle="tooltip" data-bs-placement="left"
                      data-bs-custom-class="custom-tooltip"
                      data-bs-title="Agendar cita">
                <i class="fas fa-calendar-plus"></i>
            </a>
        </div>';
        return $data;
    }

    public function generateVeterinarioControlData(array $item): string
    {
        return '';
    }
    public function modifyInfoColumn(array $item): string
    {
        return '';
    }
}
