<?php

namespace app\models;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\model\Model;
use app\core\model\Rules;

class Services_has_project extends Model
{
    protected $table = 'services_has_projects';
    protected $fillable = ['services_id', 'projects_id', 'precio_venta_publico', 'status'];

    public function __construct()
    {
        parent::__construct();
        // TODO: Crear reglas de negocio aca.
        // $this->rules->add(
        //     'name',
        //     (new Rules())
        //         ->unique($this->table, 'name')
        //         ->get()
        // );
    }
}
