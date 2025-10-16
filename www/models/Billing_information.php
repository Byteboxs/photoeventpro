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
class Billing_information extends Model
{
    protected $table = 'billing_information';
    protected $fillable = ['customer_id', 'document_type_id', 'tipo_persona', 'razon_social', 'nit', 'direccion_facturacion', 'status'];

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
}