<?php

namespace app\services\ui\menu;

class MenuBuilder implements MenuBuilderInterface
{
    private array $items = [];

    public function addItem(MenuItemInterface $item): MenuBuilderInterface
    {
        $this->items[] = $item;
        return $this;
    }

    public function addSeparator(string $label): MenuBuilderInterface
    {
        $this->items[] = new MenuSeparator($label);
        return $this;
    }

    public function build(): Menu
    {
        return new Menu($this->items);
    }
}
