<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Institution
 * 
 * This class represents the 'institutions' table in the database.
 * 
 */
class Institution extends Model
{
    protected $table = 'institutions';
    protected $fillable = ['nombre', 'status'];

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