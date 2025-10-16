<?php

namespace app\core\database\builder;

class ParametersBuilder implements ParametersConstructor
{
    private array $parameters = [];

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function addParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function merge(ParametersConstructor $otherParams)
    {
        foreach ($otherParams->getParameters() as $name => $value) {
            $this->addParameter($name, $value);
        }
    }
}
