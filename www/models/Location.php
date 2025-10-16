<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Location
 * 
 * This class represents the 'locations' table in the database.
 * 
 */
class Location extends Model
{
    protected $table = 'locations';
    protected $fillable = ['nombre', 'direccion', 'status'];

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