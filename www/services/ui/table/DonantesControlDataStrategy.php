<?php

namespace app\services\ui\table;



class DonantesControlDataStrategy implements ControlDataStrategy
{

    public function generateControlData(array $item): string
    {
        $id = $item['animal_id'];
        $data = '
        <div class="btn-group">
            <a class="btn btn-outline-secondary" href="' . APP_DIRECTORY_PATH . '/donar/' . $id . '" data-bs-toggle="tooltip" data-bs-placement="left"
                      data-bs-custom-class="custom-tooltip"
                      data-bs-title="Donar">
                <i class="fas fa-syringe"></i>
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
