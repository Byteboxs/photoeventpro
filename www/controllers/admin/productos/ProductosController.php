<?php

namespace app\controllers\admin\productos;


class ProductosController
{
    public function productosView(...$request)
    {
        (new UIProductosController)->draw(...$request);
    }

    public function detalleView(...$request)
    {
        (new UIDetalleProductoController)->draw(...$request);
    }

    public function crearProductoView(...$request)
    {
        (new UIAgregarProductoController)->draw(...$request);
    }

    public function crearProductoAction(...$request)
    {
        (new ActionCrearProductoController)->run(...$request);
    }

    public function editarProductoView(...$request)
    {
        (new UIEditarProductoController)->draw(...$request);
    }

    public function editarProductoAction(...$request)
    {
        (new ActionEditarProductoController)->run(...$request);
    }
}
