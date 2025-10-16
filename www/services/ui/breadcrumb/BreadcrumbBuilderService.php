<?php

namespace app\services\ui\breadcrumb;

class BreadcrumbBuilderService
{
    private array $items = [];

    private static $instance = null;

    private function __construct() {}

    public static function create()
    {
        if (is_null(self::$instance)) {
            self::$instance = new BreadcrumbBuilderService();
        }
        return self::$instance;
    }

    // Método para agregar un enlace al breadcrumb
    public function addLink(string $url, string $label): self
    {
        $this->items[] = new BreadcrumbLink($url, $label);
        return $this;
    }

    // Método para agregar un elemento activo (el último)
    public function addActive(string $label): self
    {
        $this->items[] = new BreadcrumbActive($label);
        return $this;
    }

    // Método para construir y generar el breadcrumb
    public function build(): string
    {
        $html = '<ol class="breadcrumb float-sm-right">';
        foreach ($this->items as $item) {
            $html .= $item->render();
        }
        $html .= '</ol>';
        return $html;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
