<?php

namespace app\services\ui\form;

class CheckboxElement extends AbstractFormElement
{
    public function render(): string
    {
        // $checked = $this->value ? ' checked' : '';
        return sprintf(
            '<input type="checkbox" name="%s" value="%s" %s>',
            $this->name,
            $this->value,
            $this->renderAttributes()
        );
    }
}
