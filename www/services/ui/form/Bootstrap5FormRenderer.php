<?php

namespace app\services\ui\form;

class Bootstrap5FormRenderer implements FormRendererInterface
{
    public function render(Form $form): string
    {
        $html = '';
        foreach ($form->getElements() as $element) {
            $html .= $this->renderElement($element);
        }
        // $html .= '</form>';
        return $html;
    }

    public function renderElement(FormElementInterface $element): string
    {
        if ($element instanceof InputElement && $element->getName() === 'submit') {
            return sprintf('<div class="mb-0">%s</div>', $element->render());
        }

        if ($element instanceof InputElement && $element->getType() === 'hidden') {
            return $element->render();
        }

        if ($element instanceof CheckboxElement) {
            return sprintf(
                '<div class="mb-0 form-check">
                    %s
                    <label class="form-check-label" for="%s">%s</label>
                </div>',
                $element->render(),
                $element->getId(),
                $element->getLabel()
            );
        }

        if ($element instanceof RadioElement) {
            return sprintf(
                '<div class="mb-0">
                    <label class="form-label">%s</label>
                    %s
                </div>',
                $element->getLabel(),
                $element->render()
            );
        }

        if ($element->isFloatingLabel()) {
            return sprintf(
                '<div class="form-floating mb-0">
                    %s
                    <label for="%s">%s</label>
                </div>',
                $element->render(),
                $element->getId(),
                $element->getLabel()
            );
        }


        return sprintf(
            '<div class="mb-0">
                    <label for="%s" class="form-label">%s</label>
                    %s
                </div>',
            $element->getId(),
            $element->getLabel(),
            $element->render()
        );
    }
}
