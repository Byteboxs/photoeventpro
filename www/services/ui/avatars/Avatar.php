<?php

namespace app\services\ui\avatars;

use app\services\ui\html\HtmlFactory;

class Avatar
{
    private $color;
    private $avatarContent;
    private $title;
    private $subtitle;
    private $url;
    private $tooltip;
    private $target = '_self';

    public function __construct($avatarContent, $title, $subtitle, $url = 'javascript:void(0)', $tooltip = '', $color = 'primary')
    {
        $this->color = $color;
        $this->avatarContent = $avatarContent;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->url = $url;
        $this->tooltip = $tooltip;
    }

    public function setTareget($target)
    {
        $this->target = $target;
    }

    private function render()
    {
        $link = HtmlFactory::create(
            'a',
            [
                'href' => $this->url,
                'style' => 'color: var( --bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)))',
                'target' => $this->target,
                'data-bs-toggle' => 'tooltip',
                'data-bs-placement' => 'top',
                'data-bs-original-title' => $this->tooltip,
            ]
        );

        $spanAvatar = HtmlFactory::create('span', [
            'class' => 'avatar-initial rounded-circle pull-up text-heading bg-label-' . $this->color,
        ])->addChild($this->avatarContent);

        $divAvatar = HtmlFactory::create(
            'div',
            [
                'class' => 'avatar avatar-sm me-4'
            ]
        )->addChild($spanAvatar);

        $divAvatarWrapper = HtmlFactory::create('div', [
            'class' => 'avatar-wrapper'
        ])->addChild($divAvatar);

        $spanTitle = HtmlFactory::create('span', [
            'class' => 'fw-large',
        ])->addChild($this->title);

        $smallTitle = HtmlFactory::create('small', [])->addChild($this->subtitle);

        $divContent = HtmlFactory::create('div', [
            'class' => 'd-flex flex-column'
        ])->addChild($spanTitle)->addChild($smallTitle);


        $container = HtmlFactory::create('div', [
            'class' => 'd-flex justify-content-start align-items-center user-name',
        ])->addChild($divAvatarWrapper)->addChild($divContent);

        if ($this->url != 'javascript:void(0)') {
            $link->addChild($container);
            return $link->render();
        }

        return $container->render();
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
