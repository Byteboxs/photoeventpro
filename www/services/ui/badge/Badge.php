<?php

namespace app\services\ui\badge;

class Badge implements IDrawable
{
    protected $label;
    protected $color;

    public function __construct($label, $color = 'primary')
    {
        $this->label = $label;
        $this->color = $color;
    }

    public function render(): string
    {
        return '<span class="badge bg-' . $this->color . '">' . $this->label . '</span>';
    }

    public function __tostring(): string
    {
        return $this->render();
    }
}
