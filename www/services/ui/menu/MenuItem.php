<?php

namespace app\services\ui\menu;

class MenuItem implements MenuItemInterface
{
    private string $label;
    private ?string $icon;
    private ?string $link;
    private array $subItems;
    private bool $active;
    private bool $external;

    public function __construct(
        string $label,
        ?string $icon = null,
        ?string $link = null,
        array $subItems = [],
        bool $active = false,
        bool $external = false
    ) {
        $this->label = $label;
        $this->icon = $icon;
        $this->link = $link;
        $this->subItems = $subItems;
        $this->active = $active;
        $this->external = $external;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getSubItems(): array
    {
        return $this->subItems;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isExternal(): bool
    {
        return $this->external;
    }

    public static function create(
        string $label,
        ?string $icon = null,
        ?string $link = null,
        array $subItems = [],
        bool $active = false,
        bool $external = false
    ): self {
        return new self($label, $icon, $link, $subItems, $active, $external);
    }
}
