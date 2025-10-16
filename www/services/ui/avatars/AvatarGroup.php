<?php

namespace app\services\ui\avatars;

class AvatarGroup
{
    private array $avatars = [];

    public function addAvatar(AvatarInterface $avatar): self
    {
        $this->avatars[] = $avatar;
        return $this;
    }

    public function render(): string
    {
        $avatarsHtml = '';
        foreach ($this->avatars as $avatar) {
            $avatarsHtml .= $avatar->render();
        }

        return sprintf(
            '<ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                %s
            </ul>',
            $avatarsHtml
        );
    }
}
