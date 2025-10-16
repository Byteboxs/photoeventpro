<?php

namespace app\services\ui\html;

class Link extends HtmlElement
{
    // protected string $tag = 'a';
    public function __construct(array $attributes = [], $content = null)
    {
        if (!isset($attributes['href'])) {
            $attributes['href'] = '#';
        }
        parent::__construct('a', $attributes, $content);
    }
    protected function getStartTag(): string
    {
        return '<' . $this->tag . ' ' . $this->renderAttributes() . '>';
    }
    protected function getEndTag(): string
    {
        return '</' . $this->tag . '>';
    }

    public function href(string $href): self
    {
        $this->addAttribute('href', $href);
        return $this;
    }
}
