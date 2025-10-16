<?php

namespace app\services\ui\dropdown;

class Dropdown
{
    private string $toggleIconClass = 'bx bx-dots-vertical-rounded';
    private string $toogleText = 'Opciones';
    private array $items = [];
    private string $extraClasses = '';

    public function __construct(string $toggleIconClass = 'bx bx-dots-vertical-rounded', string $toogleText = '')
    {
        $this->toggleIconClass = $toggleIconClass;
        $this->toogleText = $toogleText;
    }

    public function addItem(DropdownItemInterface $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    public function setExtraClasses(string $classes): self
    {
        $this->extraClasses = $classes;
        return $this;
    }

    public function render(): string
    {
        $itemsHtml = '';
        foreach ($this->items as $item) {
            $itemsHtml .= $item->render();
        }

        return sprintf(
            '<div class="dropdown %s">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">
                    <i class="%s"></i> %s
                </button>
                <div class="dropdown-menu">
                    %s
                </div>
            </div>',
            $this->extraClasses,
            $this->toggleIconClass,
            $this->toogleText,
            $itemsHtml
        );
    }
}
