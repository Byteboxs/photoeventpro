<?php

namespace app\services\ui\form;

class InputElement extends AbstractFormElement
{
    private string $type;

    public function __construct(string $id, string $name, string $label, string $type)
    {
        parent::__construct($id, $name, $label);
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function render(): string
    {
        $value = htmlspecialchars($this->value ?? '');
        if ($this->type === 'hidden') {
            return sprintf(
                '<input type="%s" name="%s" value="%s">',
                $this->type,
                $this->name,
                $value
            );
        } else {
            return sprintf(
                '<input type="%s" name="%s" value="%s" %s>',
                $this->type,
                $this->name,
                $value,
                $this->renderAttributes()
            );
        }
    }
}
