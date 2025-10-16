<?php

namespace app\services\ui\form;

class RadioElement extends AbstractFormElement
{
    private array $options;

    public function __construct(string $id, string $name, string $label, array $options)
    {
        parent::__construct($id, $name, $label);
        $this->options = $options;
    }

    public function render(): string
    {
        $html = '';
        foreach ($this->options as $value => $label) {
            $checked = $this->value == $value ? ' checked' : '';
            $id = $this->id . '_' . $value;
            $html .= sprintf(
                '<div class="form-check">
                <input class="form-check-input" type="radio" name="%s" id="%s" value="%s"%s %s>
                <label class="form-check-label" for="%s">%s</label>
            </div>',
                $this->name,
                $id,
                htmlspecialchars($value),
                $checked,
                $this->renderAttributes(),
                $id,
                htmlspecialchars($label)
            );
        }
        return $html;
    }
}
