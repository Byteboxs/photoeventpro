<?php

namespace app\services\ui\form;

class FormBuilder
{
    private Form $form;

    public function __construct()
    {
        $this->form = new Form();
    }

    public function setAttribute(string $name, string $attribute, $value): self
    {
        $element = $this->form->getElement($name);
        if ($element) {
            $element->setAttribute($attribute, $value);
        }
        return $this;
    }
    public function setValue(string $name, $value): self
    {
        $element = $this->form->getElement($name);
        if ($element) {
            $element->setValue($value);
        }
        return $this;
    }

    public function removeAttribute(string $name, string $attribute): self
    {
        $element = $this->form->getElement($name);
        if ($element) {
            $element->removeAttribute($attribute);
        }
        return $this;
    }

    public function addInput(string $id, string $name, string $type, string $label = ''): self
    {
        $element = new InputElement($id, $name, $label, $type);
        $element->setAttribute('class', 'form-control');
        $this->form->addElement($element);

        return $this;
    }

    public function addSelect(string $id, string $name, string $label, array $options): self
    {
        $element = new SelectElement($id, $name, $label, $options);
        $element->setAttribute('class', 'form-select');
        $this->form->addElement($element);
        return $this;
    }

    public function addTextarea(string $id, string $name, string $label): self
    {
        $element = new TextareaElement($id, $name, $label);
        $element->setAttribute('class', 'form-control');
        $this->form->addElement($element);
        return $this;
    }

    public function addCheckbox(string $id, string $name, string $label): self
    {
        $element = new CheckboxElement($id, $name, $label);
        $element->setAttribute('class', 'form-check-input');
        $this->form->addElement($element);
        return $this;
    }

    public function addSwitchCheckbox(string $id, string $name, string $label): self
    {
        $element = new SwitchCheckboxElement($id, $name, $label);
        $element->setAttribute('class', 'form-check-input');
        $this->form->addElement($element);
        return $this;
    }

    public function addRadio(string $id, string $name, string $label, array $options): self
    {
        $element = new RadioElement($id, $name, $label, $options);
        $element->setAttribute('class', 'form-check-input');
        $this->form->addElement($element);
        return $this;
    }

    public function setId(string $name, string $id): self
    {
        $element = $this->form->getElement($name);
        if ($element) {
            $element->setId($id);
        }
        return $this;
    }

    public function setFloatingLabel(string $name, bool $floating): self
    {
        $element = $this->form->getElement($name);
        if ($element) {
            $element->setFloatingLabel($floating);
        }
        return $this;
    }

    public function addCssClass(string $name, string $class): self
    {
        $element = $this->form->getElement($name);
        if ($element) {
            $element->addCssClass($class);
        }
        return $this;
    }

    public function setPlaceholder(string $name, string $placeholder): self
    {
        $element = $this->form->getElement($name);
        if ($element) {
            $element->setPlaceholder($placeholder);
        }
        return $this;
    }

    public function build(): Form
    {
        return $this->form;
    }
}
