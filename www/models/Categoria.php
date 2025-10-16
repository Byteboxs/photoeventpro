<?php

namespace app\models;

use app\core\Application;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Categoria
 * 
 * This class represents the 'categorias' table in the database.
 * 
 */
class Categoria extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['nombre', 'descripcion', 'status'];

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

    public static function getCategorias()
    {
        $builder = Application::$app->builder;
        $sql = "SELECT
                    id,
                    nombre,
                    descripcion,
                    status
                FROM
                    categorias
                WHERE
                    status = 'activo';
        ";
        $stmt = $builder->run($sql);
        $result = $stmt->fetchAll();
        if (!$result) {
            $result = [];
        }
        return $result;
    }
}
