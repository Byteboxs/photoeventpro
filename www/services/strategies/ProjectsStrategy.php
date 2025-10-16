<?php

namespace app\services\strategies;

use app\helpers\DateHelper;
use app\helpers\RouteHelper;
use app\services\ui\avatars\AvatarGroup;
use app\services\ui\avatars\CounterAvatar;
use app\services\ui\avatars\ImageAvatar;
use app\services\ui\badge\Badge;
use app\services\ui\badge\BadgeBuilderService;
use app\services\ui\dropdown\Dropdown;
use app\services\ui\dropdown\LinkDropdownItem;
use app\services\ui\html\HtmlFactory;

class ProjectsStrategy
{
    public function addAvatar($item, $args)
    {
        $id = $item[$args['idName']];
        $linkDetalleProyecto = $args['linkDetalleProyecto'] . $id;
        $path = $args['path'];
        $avatarGroup = new AvatarGroup();
        $avatarGroup->addAvatar(new CounterAvatar('PG', $linkDetalleProyecto, 'Ver evento')); // +5
        return $avatarGroup->render();
    }
    public function addControls($item, $args)
    {
        $id = $item[$args['idName']];
        $registrarClienteLink = $args['linkRegistrarCliente'] . $id;
        $linkEditarProyecto = RouteHelper::getUrlFor('editarEventoView', ['evento_id' => $id]);
        $linkVerProyecto = $args['linkVerProyecto'] . $id;
        $linkPosEfectivo = $args['linkPosEfectivo'] . $id;
        $estado = $item[$args['estado']];

        $links = [
            'programado' => [
                ['link' => $linkVerProyecto, 'icon' => 'bx bx-link-external', 'text' => 'Ver'],
                ['link' => $registrarClienteLink, 'icon' => 'fas fa-user-plus', 'text' => 'Registrar Cliente'],
                ['link' => $linkPosEfectivo, 'icon' => 'fas fa-cash-register', 'text' => 'Pago Efectivo'],

            ],
            'activo' => [
                ['link' => $linkVerProyecto, 'icon' => 'bx bx-link-external', 'text' => 'Ver'],
                ['link' => $linkEditarProyecto, 'icon' => 'bx bx-edit-alt', 'text' => 'Editar'],
                ['link' => $registrarClienteLink, 'icon' => 'fas fa-user-plus', 'text' => 'Registrar Cliente'],
            ],
            'finalizado' => [
                ['link' => 'javascript:void(0);', 'icon' => 'bx bx-link-external', 'text' => 'Ver'],
            ],
            'cancelado' => [
                ['link' => 'javascript:void(0);', 'icon' => 'bx bx-link-external', 'text' => 'Ver'],
            ],
        ];

        $dropdown = new Dropdown();
        foreach ($links[$estado] as $link) {
            $dropdown->addItem(new LinkDropdownItem($link['link'], $link['icon'], $link['text']));
        }

        return $dropdown->render();
    }

    public function modifyEstado($item, $args)
    {
        $estado = $item["estado"];
        $badgesConfig = [
            'programado' => ['label' => 'Programado', 'color' => 'warning'],
            'activo' => ['label' => 'Activo', 'color' => 'success'],
            'finalizado' => ['label' => 'Finalizado', 'color' => 'dark'],
            'cancelado' => ['label' => 'Cancelado', 'color' => 'danger'],
            'default' => ['label' => 'Defecto', 'color' => 'light'],
        ];
        $badgeConfig = $badgesConfig[$estado] ?? $badgesConfig['default'];
        $badge = new Badge($badgeConfig['label'], $badgeConfig['color']);
        return $badge->render();
    }

    public function modifyHora($item, $args)
    {
        return DateHelper::toAmPmTime($item[$args['column']]);
    }
    public function modifyNombre($item, $args)
    {

        $id = $item[$args['idName']];
        $linkDetalleProyecto = $args['linkDetalleProyecto'] . $id;
        $evento = $item[$args['column']];
        $eventoArray = explode(' ', $evento);
        $primerCaracter = strtoupper(str_split($eventoArray[0])[0]);
        $segundoCaracter = strtoupper(str_split($eventoArray[1])[0]);

        $aleatorio = rand(0, 6);
        $colors = [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'dark',
        ];

        $spanLink = HtmlFactory::create('span', ['class' => 'fw-large'], $evento);
        $contentLink = HtmlFactory::create('div', ['class' => 'd-flex flex-column']);
        $contentLink->addChild($spanLink);
        $link = HtmlFactory::create('a', ['href' => $linkDetalleProyecto, 'style' => 'color: var( --bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)))']);
        $link->addChild($contentLink);

        $content = HtmlFactory::create('div', ['class' => 'd-flex justify-content-start align-items-center user-name']);
        $avatarWraper = HtmlFactory::create('div', ['class' => 'avatar-wrapper']);
        $avatar = HtmlFactory::create('div', ['class' => 'avatar avatar-sm me-4']);
        $avatarLink = HtmlFactory::create('a', ['href' => $linkDetalleProyecto]);
        $avatarInitial = HtmlFactory::create('span', [
            'class' => 'avatar-initial rounded-circle pull-up text-heading bg-label-' . $colors[$aleatorio],
            'data-bs-toggle' => 'tooltip',
            'data-bs-original-title' => 'Ir a ' . mb_strtolower($evento),

        ], $primerCaracter . $segundoCaracter);
        $avatarLink->addChild($avatarInitial);
        $avatar->addChild($avatarLink);
        $avatarWraper->addChild($avatar);
        $content->addChild($avatarWraper);
        $content->addChild($link);

        // $avatar = $this->addAvatar($item, $args);
        // return $avatar . HtmlFactory::create('a', ['href' => $linkDetalleProyecto],);
        return $content;
    }
}
?>
<!-- <div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-4">
            <a href="/photoeventpro/eventos/1/cliente/1/detalle">
                <span class="avatar-initial rounded-circle bg-label-success">AO</span>
            </a>

        </div>
    </div>
    <a href="/photoeventpro/eventos/1/cliente/1/detalle" style="color: var( --bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)) );">
        <div class="d-flex flex-column">
            <span class="fw-large">Andrés Alonso Otálora Mosquera</span>
            <small>siscore.soluciones@gmail.com</small>
        </div>
    </a>
</div>

<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-4">
            <a href="/photoeventpro/evento/detalle/1">
                <span class="avatar-initial rounded-circle bg-label-success">E</span>
            </a>
        </div>
    </div>
    <a href="/photoeventpro/evento/detalle/1">
        <divarray>
            <span class="fw-large">GRADUACIÓN INGENIERIA DE SISTEMAS 2025-1</span>
        </divarray>
    </a>
</div> -->