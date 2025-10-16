<?php

namespace app\services\ui\html;

class Image extends HtmlElement
{
    protected string $tag = 'img';
    public function __construct(array $attributes = [], $content = null)
    {
        parent::__construct('img', $attributes, $content);
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
