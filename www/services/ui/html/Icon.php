<?php

namespace app\services\ui\html;

class Icon extends HtmlElement
{

    protected string $tag = 'i';

    public function __construct(array $attributes = [])
    {
        parent::__construct('i', $attributes);
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
