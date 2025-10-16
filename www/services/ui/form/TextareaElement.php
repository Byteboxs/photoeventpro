<?php

namespace app\services\ui\form;

class TextareaElement extends AbstractFormElement
{
    public function render(): string
    {
        $value = htmlspecialchars($this->value ?? '');
        return sprintf(
            '<textarea name="%s" %s>%s</textarea>',
            $this->name,
            $this->renderAttributes(),
            $value
        );
    }
}
