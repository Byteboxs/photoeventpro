<?php

namespace app\services\ui\form;

class SwitchCheckboxElement extends AbstractFormElement
{
    public function render(): string
    {
        return sprintf(
            '
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="%s" value="%s" %s>
            </div>',
            $this->name,
            $this->value,
            $this->renderAttributes(),
            $this->label
        );
    }
}
