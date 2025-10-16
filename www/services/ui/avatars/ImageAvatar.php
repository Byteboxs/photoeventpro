<?php

namespace app\services\ui\avatars;

class ImageAvatar implements AvatarInterface
{
    private string $src;
    private string $alt;
    private string $name; // Para el tooltip

    public function __construct(string $src, string $alt, string $name)
    {
        $this->src = $src;
        $this->alt = $alt;
        $this->name = $name;
    }

    public function render(): string
    {
        return sprintf(
            '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="%s" data-bs-original-title="%s">
                <img src="%s" alt="%s" class="rounded-circle">
            </li>',
            $this->name,
            $this->name,
            $this->src,
            $this->alt
        );
    }
}
