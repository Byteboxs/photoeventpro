<?php

namespace app\core\database\builder;

interface ParametersConstructor
{
    public function getParameters(): array;
    public function addParameter(string $name, $value): void;
}
