<?php

namespace app\services\ui\table;

interface ControlDataStrategy
{
    public function generateControlData(array $item): string;
    public function generateVeterinarioControlData(array $item): string;
    public function modifyInfoColumn(array $item): string;
}
