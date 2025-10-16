<?php

namespace app\services\ui\html;

class HtmlFactory
{
    public static function create(string $type, array $attributes = [], $content = null)
    {
        return match ($type) {
            'button' => new Button($attributes, $content),
            'link' => new Link($attributes, $content),
            'img' => new Image($attributes, $content),
            'icon' => new Icon($attributes),
            default => new HtmlElement($type, $attributes, $content),
        };
    }
}
