<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Document_type
 * 
 * This class represents the 'document_type' table in the database.
 * 
 */
class Document_type extends Model
{
    protected $table = 'document_type';
    protected $fillable = ['nombre', 'codigo', 'status'];

    public function __construct()
    {
        parent::__construct();
        // TODO: Crear reglas de negocio aca.
        $this->rules->add(
            'nombre',
            (new Rules())
                ->unique($this->table, 'nombre')
                ->get()
        );
    }
}
