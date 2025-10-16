<?php

namespace app\controllers\order;

class OrdersController
{
    public function __construct() {}
    public function ordersView(...$args)
    {
        (new UIOrdersController(...$args))->draw();
    }
}
