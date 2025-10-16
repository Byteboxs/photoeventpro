<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Shipping_information
 * 
 * This class represents the 'shipping_information' table in the database.
 * 
 */
class Shipping_information extends Model
{
    protected $table = 'shipping_information';
    protected $fillable = ['customer_id', 'nombre_contacto', 'direccion_envio', 'telefono_contacto', 'instrucciones_adicionales'];

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