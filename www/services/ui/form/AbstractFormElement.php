<?php

namespace app\services\ui\form;

abstract class AbstractFormElement implements FormElementInterface
{
    protected string $name;
    protected string $id;
    protected $value;
    protected string $label;
    protected array $attributes = [];
    protected bool $floatingLabel = false;
    protected array $cssClasses = [];
    protected ?string $placeholder = null;

    public function __construct(string $id, string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
        $this->id = $id;  // Generate a unique ID by default
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setAttribute(string $key, string $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute(string $key): ?string
    {
        return $this->attributes[$key] ?? null;
    }

    public function removeAttribute(string $key): void
    {
        unset($this->attributes[$key]);
    }

    public function setFloatingLabel(bool $floating): void
    {
        $this->floatingLabel = $floating;
    }

    public function isFloatingLabel(): bool
    {
        return $this->floatingLabel;
    }

    public function addCssClass(string $class): void
    {
        $this->cssClasses[] = $class;
    }

    public function setPlaceholder(string $placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    protected function renderAttributes(): string
    {
        $attributes = $this->attributes;
        $attributes['id'] = $this->id;
        if ($this->placeholder !== null) {
            $attributes['placeholder'] = $this->placeholder;
        }
        $classes = array_merge($this->cssClasses, isset($attributes['class']) ? [$attributes['class']] : []);
        if (!empty($classes)) {
            $attributes['class'] = implode(' ', array_unique($classes));
        }

        $renderedAttributes = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $renderedAttributes[] = $key;
            } elseif ($value !== false && $value !== null) {
                $renderedAttributes[] = sprintf('%s="%s"', $key, htmlspecialchars($value));
            }
        }
        return implode(' ', $renderedAttributes);
    }
}
