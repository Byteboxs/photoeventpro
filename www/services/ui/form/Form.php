<?php

namespace app\services\ui\form;

class Form
{
    private array $elements = [];

    public function addElement(FormElementInterface $element): void
    {
        $this->elements[$element->getName()] = $element;
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function getElement(string $name): ?FormElementInterface
    {
        return $this->elements[$name] ?? null;
    }
}
