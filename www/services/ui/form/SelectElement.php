<?php

namespace app\services\ui\form;

class SelectElement extends AbstractFormElement
{
    private array $options;

    public function __construct(string $id, string $name, string $label, array $options)
    {
        parent::__construct($id, $name, $label);
        $this->options = $options;
    }

    public function render(): string
    {
        $html = sprintf('<select name="%s" %s>', $this->name, $this->renderAttributes());
        $html .= '<option value="-1">Seleccione una opción</option>';
        foreach ($this->options as $value => $label) {

            $selected = $this->value == $value ? ' selected' : '';
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                htmlspecialchars($value),
                $selected,
                htmlspecialchars($label)
            );
        }
        $html .= '</select>';
        return $html;
    }
}
