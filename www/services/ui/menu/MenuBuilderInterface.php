<?php

namespace app\services\ui\menu;

interface MenuBuilderInterface
{
    public function addItem(MenuItemInterface $item): self;
    public function build(): Menu;
}
