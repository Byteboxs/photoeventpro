<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Role
 * 
 * This class represents the 'roles' table in the database.
 * 
 */
class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'description', 'status'];

    public function __construct()
    {
        parent::__construct();
        // TODO: Crear reglas de negocio aca.
        $this->rules->add(
            'name',
            (new Rules())
                ->unique($this->table, 'name')
                ->get()
        );
    }
}
