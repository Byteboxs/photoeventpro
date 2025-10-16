<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Locations_for_institution
 * 
 * This class represents the 'locations_for_institutions' table in the database.
 * 
 */
class Locations_for_institution extends Model
{
    protected $table = 'locations_for_institutions';
    protected $fillable = ['location_id', 'institucion_id', 'status'];

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