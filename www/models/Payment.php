<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Billing_information
 * 
 * This class represents the 'billing_information' table in the database.
 * 
 */
class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'purchase_orders_id',
        'amount',
        'payment_method',
        'status'
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

    public static function createPayment($purchaseOrdersId, $amount, $paymentMethod, $status): array
    {
        $payment = new self();
        $payment->purchase_orders_id = $purchaseOrdersId;
        $payment->amount = $amount;
        $payment->payment_method = $paymentMethod;
        $payment->status = $status;

        if ($payment->save()) {
            return ['status' => 'success', 'message' => 'Pago creado exitosamente.'];
        } else {
            return ['status' => 'fail', 'message' => 'Error al crear el pago.'];
        }
    }
}
