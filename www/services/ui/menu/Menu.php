<?php

namespace app\services\ui\menu;

class Menu
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function draw()
    {
        echo $this->render();
    }

    public function render(string $menuClass = 'menu-inner'): string
    {
        $output = "<ul class=\"{$menuClass} py-1\">";

        foreach ($this->items as $item) {
            $output .= $this->renderMenuItem($item);
        }

        $output .= "</ul>";
        return $output;
    }

    private function renderMenuItem(MenuItemInterface $item, int $depth = 0): string
    {
        // Manejar separadores
        if ($item instanceof MenuSeparator) {
            return "<li class=\"menu-header small text-uppercase\">
                <span class=\"menu-header-text\">" .
                htmlspecialchars($item->getLabel()) .
                "</span>
            </li>";
        }

        $hasSubItems = !empty($item->getSubItems());
        $isActive = $item->isActive() ? 'active' : '';
        $isOpen = $hasSubItems ? '' : 'open';

        $menuItemClass = trim("menu-item {$isActive}");
        $menuLinkClass = $hasSubItems ? "menu-link menu-toggle" : "menu-link";

        $output = "<li class=\"{$menuItemClass}\">";
        $output .= "<a href=\"" . ($item->getLink() ?? 'javascript:void(0);') . "\" class=\"{$menuLinkClass}\">";

        if ($item->getIcon()) {
            $output .= "<i class=\"menu-icon tf-icons {$item->getIcon()}\"></i>";
        }

        $output .= "<div class=\"text-truncate\">" . htmlspecialchars($item->getLabel()) . "</div>";
        $output .= "</a>";

        if ($hasSubItems) {
            $output .= $this->renderSubItems($item->getSubItems());
        }

        $output .= "</li>";

        return $output;
    }

    private function renderSubItems(array $subItems): string
    {
        if (empty($subItems)) return '';

        $output = "<ul class=\"menu-sub\">";
        foreach ($subItems as $subItem) {
            $output .= $this->renderMenuItem($subItem, 1);
        }
        $output .= "</ul>";

        return $output;
    }
}
