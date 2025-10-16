<?php

namespace app\services\strategies;

use app\services\ui\avatars\AvatarGroup;
use app\services\ui\avatars\CounterAvatar;
use app\services\ui\avatars\ImageAvatar;
use app\services\ui\dropdown\Dropdown;
use app\services\ui\dropdown\LinkDropdownItem;

class ProyectosAvatarGroupStrategy
{
    public function add($item, $args)
    {
        $id = $item[$args['idName']];
        $link = $args['link'] . $id;
        $path = $args['path'];
        $avatarGroup = new AvatarGroup();

        $avatarGroup->addAvatar(new ImageAvatar($path . '/avatars/5.png', 'Avatar 1', 'Nombre Apellido 1'));
        $avatarGroup->addAvatar(new ImageAvatar($path . '/avatars/6.png', 'Avatar 2', 'Nombre Apellido 2'));
        $avatarGroup->addAvatar(new ImageAvatar($path . '/avatars/7.png', 'Avatar 3', 'Nombre Apellido 3'));
        $avatarGroup->addAvatar(new CounterAvatar(5, $link)); // +5

        return $avatarGroup->render();
    }
}
