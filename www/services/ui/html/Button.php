<?php

namespace app\services\ui\html;

class Button extends HtmlElement
{

    protected string $tag = 'button';

    public function __construct(array $attributes = [], $content = null)
    {
        if (!isset($attributes['type'])) {
            $attributes['type'] = 'button';
        }
        parent::__construct('button', $attributes, $content);
    }

    protected function getStartTag(): string
    {
        return '<' . $this->tag . ' ' . $this->renderAttributes() . '>';
    }
    protected function getEndTag(): string
    {
        return '</' . $this->tag . '>';
    }
}
