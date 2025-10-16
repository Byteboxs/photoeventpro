<?php

namespace app\services\ui\form;

interface FormElementInterface
{
    public function render(): string;
    public function getName(): string;
    public function getId(): string;
    public function setId(string $id): void;
    public function setValue($value): void;
    public function getValue();
    public function getLabel(): string;
    public function setFloatingLabel(bool $floating): void;
    public function isFloatingLabel(): bool;
    public function addCssClass(string $class): void;
    public function setPlaceholder(string $placeholder): void;
    public function getPlaceholder(): ?string;
    public function setAttribute(string $key, string $value): void;
    public function getAttribute(string $key): ?string;
    public function removeAttribute(string $key): void;
}
