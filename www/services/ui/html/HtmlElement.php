<?php

namespace app\services\ui\html;

class HtmlElement extends Element
{
    protected string $tag;
    private $hasEndTag = true;
    private array $childrens;

    public function __construct(string $tag = '', array $attributes = [], $content = null)
    {
        parent::__construct($attributes, $content);
        $this->tag = $tag;
    }

    public function addChild($child)
    {
        if ($child == null) {
            return $this;
        }
        $this->childrens[] = $child;

        // $oldContent = $this->getContent();
        $this->setContent(null);
        $newContent = '';
        foreach ($this->childrens as $child) {
            $newContent .= $child;
        }
        // $newContent .= $oldContent;
        $this->setContent($newContent);
        return $this;
    }
    public function activateEndTag(): void
    {
        $this->hasEndTag = true;
    }

    public function deactivateEndTag(): void
    {
        $this->hasEndTag = false;
    }

    protected function getStartTag(): string
    {
        return '<' . $this->tag . ' ' . $this->renderAttributes() . '>';
    }
    protected function getEndTag(): string
    {
        if (!$this->hasEndTag) {
            return '';
        }
        return '</' . $this->tag . '>';
    }
}
