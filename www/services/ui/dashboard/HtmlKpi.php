<?php

namespace app\services\ui\dashboard;

use app\services\ui\html\HtmlFactory;

class HtmlKpi
{
    private string $icon;
    private $name;
    private $value;
    private $color;

    public function __construct(string $icon, $name, $value, $color)
    {
        $this->icon = $icon;
        $this->name = $name;
        $this->value = $value;
        $this->color = $color;
    }
    private function getIcon()
    {
        return HtmlFactory::create('div', [])->addChild(
            HtmlFactory::create('i', ['class' => $this->icon . ' mx-2'])
        );
    }

    private function getAvatar()
    {
        return HtmlFactory::create('div', ['class' => 'avatar avatar-lg'])
            ->addChild(
                HtmlFactory::create(
                    'div',
                    [
                        'class' => 'avatar-initial bg-label-' . $this->color . ' rounded'
                    ]
                )->addChild($this->getIcon())
            );
    }

    private function getContentRight()
    {
        return HtmlFactory::create(
            'div',
            [
                'class' => 'content-right'
            ]
        )
            ->addChild(
                HtmlFactory::create(
                    'p',
                    [
                        'class' => 'mb-0 fw-medium'
                    ],
                    $this->name
                )
            )
            ->addChild(
                HtmlFactory::create(
                    'h4',
                    [
                        'class' => 'text-' . $this->color . ' mb-0'
                    ]

                )->addChild($this->value)
            );
    }
    private function getKpi()
    {
        return HtmlFactory::create(
            'div',
            [
                'class' => 'd-flex align-items-center gap-4 me-6 me-sm-0'
            ]
        )
            ->addChild($this->getAvatar())
            ->addChild($this->getContentRight());
    }

    public function render(): string
    {
        return $this->getKpi();
    }

    public function __tostring()
    {
        return $this->render();
    }
}
