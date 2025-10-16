<?php

namespace app\services\ui\html;

use app\core\collections\Map;

abstract class Element
{
    protected string $tag;
    protected $content = null;
    protected Map $attributes;

    public function __construct(array $attributes = [], $content = null)
    {
        $this->attributes = new Map();
        $this->attributes->addArray($attributes);
        $this->content = $content;
    }

    public function addAttribute($key, $value)
    {
        $this->attributes->put($key, $value);
    }

    public function getAttribute($key)
    {
        return $this->attributes->get($key) ?? null;
    }

    public function removeAttribute($key)
    {
        if ($this->attributes->contains($key)) {
            $this->attributes->remove($key);
        }
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    abstract protected function getStartTag(): string;
    abstract protected function getEndTag(): string;

    protected function renderContent(): string
    {
        if ($this->content instanceof Element) {
            return $this->content->render();
        } elseif (is_string($this->content)) {
            return $this->content;
        }
        return '';
    }

    public function render(): string
    {
        $output = $this->getStartTag();
        $output .= $this->renderContent();
        $output .= $this->getEndTag();
        return $output;
    }

    protected function renderAttributes(): string
    {
        $renderedAttributes = [];
        foreach ($this->attributes->toArray() as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            if ($value === true) {
                $renderedAttributes[] = $key;
            } elseif ($value !== false && $value !== null) {
                $renderedAttributes[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            }
        }
        return implode(' ', $renderedAttributes);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
