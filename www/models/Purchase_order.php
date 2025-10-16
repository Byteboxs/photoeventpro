<?php

namespace app\models;

use app\core\Application;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Purchase_order
 * 
 * This class represents the 'purchase_orders' table in the database.
 * 
 */
class Purchase_order extends Model
{
    protected $table = 'purchase_orders';
    protected $fillable = [
        'customer_id',
        'salesperson_id',
        'billing_information_id',
        'project_id',
        'shipping_information_id',
        'fecha_orden',
        'total_bruto',
        'total_neto',
        'estado_pago'
    ];

    public function __construct()
    {
        parent::__construct();
        // TODO: Crear reglas de negocio aca.
        // $this->rules->add(
        //     'column',
        //     (new Rules())
        //         ->rules($this->table, 'column', 'message')
        //         ->get()
        // );
    }
    public static function getNumberOfOrdersPendingPrint(): int
    {
        $builder = Application::$app->builder;
        $sql = "SELECT COUNT(DISTINCT po.id) AS numero_pedidos_pendientes_impresion
                FROM `purchase_orders` po
                JOIN `order_details` od ON po.id = od.purchase_order_id
                JOIN `services` s ON od.service_id = s.id
                JOIN `categorias` cg ON s.categoria_id = cg.id
                WHERE po.estado_pago = 'pagado'
                AND cg.nombre = 'Impresion'
                AND EXISTS (
                    SELECT 1
                    FROM `selected_pictures` sp
                    WHERE sp.order_detail_id = od.id
                );
        ";
        $params = [];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result->numero_pedidos_pendientes_impresion;
        }
        return 0;
    }
}
