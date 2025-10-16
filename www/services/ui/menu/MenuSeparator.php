<?php

namespace app\services\ui\menu;

class MenuSeparator implements MenuItemInterface
{
    private string $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIcon(): ?string
    {
        return null;
    }

    public function getLink(): ?string
    {
        return null;
    }

    public function getSubItems(): array
    {
        return [];
    }

    public function isActive(): bool
    {
        return false;
    }
}
