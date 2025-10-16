<?php

namespace app\services\ui\menu;

interface MenuItemInterface
{
    public function getLabel(): string;
    public function getIcon(): ?string;
    public function getLink(): ?string;
    public function getSubItems(): array;
    public function isActive(): bool;
}
