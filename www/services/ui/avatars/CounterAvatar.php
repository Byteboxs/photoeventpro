<?php

namespace app\services\ui\avatars;

class CounterAvatar implements AvatarInterface
{
    private $count;
    private $tooltip;
    private string $link;

    public function __construct($count, string $link = 'javascript:void(0);', $tooltip = null)
    {
        $this->count = $count;
        $this->link = $link;
        $this->tooltip = $tooltip;
    }

    public function render(): string
    {
        return sprintf(
            '<li class="avatar avatar-sm">
                <a href="%s">
                    <span class="avatar-initial rounded-circle pull-up text-heading" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="bottom" 
                    data-bs-original-title="%s">%s</span>
                </a>
            </li>',
            $this->link,
            $this->tooltip,
            $this->count
        );
    }
}
