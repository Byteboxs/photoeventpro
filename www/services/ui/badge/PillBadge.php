<?php

namespace app\services\ui\badge;

class PillBadge extends Badge
{
    public function render(): string
    {
        return sprintf('<span class="badge rounded-pill %s">%s</span>', $this->color, $this->label);
    }
}
