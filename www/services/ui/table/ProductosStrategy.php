<?php

namespace app\services\ui\table;

class ProductosStrategy
{
    public function modifyDisponibleVenta(array $item)
    {
        if ($item['disponible_venta'] === 1) {
            $bgt = 'text-bg-primary';
            $msg = 'Si';
        } else {
            $bgt = 'text-bg-danger';
            $msg = 'No';
        }
        return sprintf('<span class="badge %s">%s</span>', $bgt, $msg);
    }
}
