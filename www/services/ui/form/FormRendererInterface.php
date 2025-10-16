<?php

namespace app\services\ui\form;

interface FormRendererInterface
{
    public function render(Form $form): string;
    public function renderElement(FormElementInterface $element): string;
}
