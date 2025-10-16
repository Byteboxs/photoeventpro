<?php

namespace app\services\ui\breadcrumb;

class BreadcrumbLink implements BreadcrumbItem
{
    private string $url;
    private string $label;

    public function __construct(string $url, string $label)
    {
        $this->url = $url;
        $this->label = $label;
    }

    public function render(): string
    {
        return sprintf('<li class="breadcrumb-item" style="color: #1D3557; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);"><a href="%s">%s</a></li>', $this->url, $this->label);
    }
}
