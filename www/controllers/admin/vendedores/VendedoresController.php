<?php

namespace app\controllers\admin\vendedores;

class VendedoresController
{
    public function __construct() {}
    public function vendedoresView(...$args): void
    {
        (new UIVendedores(...$args))->draw();
    }
    public function vendedoresRegistrarView(...$args): void
    {
        (new UIRegistrarVendedor(...$args))->draw();
    }

    public function vendedoresSaveAction(...$args): void
    {
        (new ActionVendedoresSave(...$args))->run();
    }
    public function vendedorUpdateView(...$args): void
    {
        (new UIVendedorUpdate(...$args))->draw();
    }
    public function vendedorUpdateAction(...$args): void
    {
        (new ActionVendedorUpdate(...$args))->run();
    }
    public function reporteRendimientoVendedorView(...$args): void
    {
        (new ReporteRendimientoVendedor(...$args))->draw();
    }
}
