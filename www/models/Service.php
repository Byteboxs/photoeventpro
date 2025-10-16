<?php

namespace app\models;

use app\core\Application;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Service
 * 
 * This class represents the 'services' table in the database.
 * 
 */
class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['categoria_id', 'nombre', 'descripcion', 'precio', 'max_fotos', 'min_fotos', 'status', 'image'];

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

    public function getTotalProductsCount()
    {
        $this->builder->select('COUNT(*) as total')
            ->from('services as s')
            ->join('categorias as c', 's.categoria_id', '=', 'c.id')
            // ->where('s.status', '=', 'activo')
            ->orderBy('s.id', 'DESC');
        $stmt = $this->builder->get();

        try {
            $this->deactivateFetchObject();
            $result = $this->fetchResult($stmt);
            $this->activateFetchObject();

            if ($result) {
                return (int)$result->total;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            error_log("Model: " . $e->getMessage());
            return 0;
        }
    }

    public function getProductos(int $page = 1)
    {
        // Validar los parámetros de paginación
        $page = max(1, $page); // Asegurar que la página sea al menos 1
        $this->perPage = max(1, $this->perPage); // Asegurar que los elementos por página sean al menos 1
        $offset = ($page - 1) * $this->perPage;

        $total = $this->getTotalProductsCount();
        if (!$total) {
            return [
                'data' => [],
                'currentPage' => $page,
                'totalPages' => 0,
                'perPage' => $this->perPage,
                'totalData' => 0
            ];
        }
        $this->builder->select('s.id AS service_id')
            ->select('c.nombre As categoria')
            ->select('s.nombre AS servicio')
            ->select('s.descripcion AS descripcion')
            ->select('s.precio AS precio')
            ->select('s.max_fotos AS max_fotos')
            ->select('s.min_fotos AS min_fotos')
            ->select('s.status AS estado')
            ->from('services as s')
            ->join('categorias as c', 's.categoria_id', '=', 'c.id')
            // ->where('s.status', '=', 'activo')
            ->orderBy('s.status', 'ASC')
            ->limitOffset($this->perPage, $offset);
        $stmt = $this->builder->get();
        try {
            $this->deactivateFetchObject();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $data = $this->fetch($stmt);

            $totalPages = ceil($total / $this->perPage);

            return [
                'data' => $data,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $this->perPage,
                'totalData' => $total
            ];
        } catch (\Exception $e) {
            error_log("Error al obtener los productos: " . $e->getMessage());
            return null;
        }
    }

    public static function getAllServices()
    {
        $builder = Application::$app->builder;
        $sql = "SELECT
                    s.id AS service_id,
                    s.nombre AS service_nombre,
                    s.descripcion AS service_descripcion,
                    s.precio,
                    s.max_fotos,
                    s.min_fotos,
                    s.image AS service_image,
                    c.id AS categoria_id,
                    c.nombre AS categoria_nombre
                FROM
                    services AS s
                JOIN
                    categorias AS c ON s.categoria_id = c.id
                WHERE
                    s.status = 'activo' AND c.status = 'activo';
        ";
        $stmt = $builder->run($sql);
        $result = $stmt->fetchAll();
        if (!$result) {
            $result = [];
        }
        return $result;
    }

    public function getProductosActivos(int $page = 1)
    {
        $this->builder->select('s.id AS service_id')
            ->select('c.nombre As categoria')
            ->select('s.nombre AS servicio')
            ->select('s.descripcion AS descripcion')
            ->select('s.precio AS precio')
            ->select('s.max_fotos AS max_fotos')
            ->select('s.min_fotos AS min_fotos')
            ->select('s.status AS estado')
            ->from('services as s')
            ->join('categorias as c', 's.categoria_id', '=', 'c.id')
            ->where('s.status', '=', 'activo');
        $stmt = $this->builder->get();
        try {
            $this->deactivateFetchObject();
            return $this->fetch($stmt);
        } catch (\Exception $e) {
            error_log("Error al obtener el producto: " . $e->getMessage());
            return null;
        }
    }

    public function getService(int $id)
    {
        $this->builder->select('s.id AS service_id')
            ->select('c.nombre As categoria')
            ->select('s.nombre AS nombre')
            ->select('s.descripcion AS descripcion')
            ->select('s.precio AS precio')
            ->select('s.max_fotos AS max_fotos')
            ->select('s.min_fotos AS min_fotos')
            ->select('s.status AS estado')
            ->from('services as s')
            ->join('categorias as c', 's.categoria_id', '=', 'c.id')
            ->where('s.id', '=', $id);
        $stmt = $this->builder->get();
        try {
            $this->deactivateFetchObject();
            return $this->fetchResult($stmt);
        } catch (\Exception $e) {
            error_log("Error al obtener el producto: " . $e->getMessage());
            return null;
        }
    }
}
