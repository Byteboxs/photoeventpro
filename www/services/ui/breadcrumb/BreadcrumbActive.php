<?php

namespace app\services\ui\breadcrumb;

class BreadcrumbActive implements BreadcrumbItem
{
    private string $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function render(): string
    {
        return sprintf('<li class="breadcrumb-item active" >%s</li>', $this->label);
    }
}
