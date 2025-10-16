<?php

namespace app\services\ui\dropdown;

class LinkDropdownItem implements DropdownItemInterface
{
    private string $href;
    private string $iconClass;
    private string $text;

    public function __construct(string $href, string $iconClass, string $text)
    {
        $this->href = $href;
        $this->iconClass = $iconClass;
        $this->text = $text;
    }

    public function render(): string
    {
        return sprintf(
            '<a class="dropdown-item" href="%s"><i class="%s me-1"></i> %s</a>',
            $this->href,
            $this->iconClass,
            $this->text
        );
    }
}
