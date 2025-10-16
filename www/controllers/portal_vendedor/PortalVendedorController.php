<?php

namespace app\controllers\portal_vendedor;

class PortalVendedorController
{
    public function __construct() {}
    public function salesPersonDashboardView(...$args): void
    {
        (new UIDashboardVendedor(...$args))->draw();
    }
    public function seleccionarEventoView(...$args): void
    {
        (new UISeleccionarEvento(...$args))->draw();
    }
    public function posView(...$args): void
    {
        (new UIPos(...$args))->draw();
    }
}
