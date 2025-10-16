<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Configuration
 * 
 * This class represents the 'configurations' table in the database.
 * 
 */
class Configuration extends Model
{
    protected $table = 'configurations';
    protected $fillable = ['config_key', 'config_value', 'config_group', 'data_type', 'is_editable', 'description'];

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