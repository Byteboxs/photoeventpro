<?php

namespace app\models;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Employe
 * 
 * This class represents the 'employes' table in the database.
 * 
 */
class Employe extends Model
{
    protected $table = 'employes';
    protected $fillable = ['user_id', 'cargo'];

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

    public static function totalDatosRendimientoVendedor(Criteria $criteria)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $sql = "SELECT COUNT(*) AS total
                FROM users AS U
                JOIN document_type AS DT
                    ON U.document_type_id = DT.id
                JOIN project_salespeople AS PS
                    ON U.id = PS.users_id
                JOIN projects AS P
                    ON PS.projects_id = P.id
                LEFT JOIN purchase_orders AS PO
                    ON P.id = PO.project_id AND U.id = PO.salesperson_id
                LEFT JOIN order_details AS OD
                    ON PO.id = OD.purchase_order_id
                WHERE {filter}
                GROUP BY
                    U.id,
                    P.id,
                    P.nombre_evento,
                    P.fecha_inicio,
                    P.fecha_fin
                ORDER BY
                    P.fecha_inicio DESC
        ";
        if ($criteria) {
            $sql = str_replace('{filter}', $criteria->getExpression(), $sql);
            $bindings = $criteria->getParameters();
        }

        // echo '<pre>';
        // var_dump($sql);
        // var_dump($bindings);
        // echo '</pre>';
        $stmt = $builder->run($sql, $bindings);
        $result = $stmt->fetch();
        if ($result) {
            return $result->total;
        }
        return 0;
    }
    public static function getDatosRendimientoVendedor(Criteria $criteria, int $page = 1)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $perPage = 10;
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $totalData = 0;
        $error = '';

        $filter = $criteria;

        $totalData = self::totalDatosRendimientoVendedor($filter);
        $totalPages = ceil($totalData / $perPage);

        if ($page > $totalPages) {
            $error = "La página $page no existe";
        }
        if (!$totalData) {
            $error = "No se encontraron registros";
            return [
                'data' => [],
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
                'totalData' => 0,
                'error' => $error
            ];
        }
        $sql = "SELECT
                    U.id AS usuario_id,
                    CONCAT_WS(' ', U.primer_nombre, U.segundo_nombre, U.primer_apellido, U.segundo_apellido) AS nombre_completo,
                    U.email AS email,
                    DT.nombre AS tipo_documento,
                    U.numero_identificacion AS numero_documento,
                    P.nombre_evento AS nombre,
                    P.fecha_inicio AS fecha_inicio_evento,
                    P.fecha_fin AS fecha_fin_evento,
                    COALESCE(SUM(PO.total_neto), 0) AS total_ventas,
                    COALESCE(SUM(OD.cantidad), 0) AS cantidad_productos_vendidos
                FROM users AS U
                JOIN document_type AS DT
                    ON U.document_type_id = DT.id
                JOIN project_salespeople AS PS
                    ON U.id = PS.users_id
                JOIN projects AS P
                    ON PS.projects_id = P.id
                LEFT JOIN purchase_orders AS PO
                    ON P.id = PO.project_id AND U.id = PO.salesperson_id
                LEFT JOIN order_details AS OD
                    ON PO.id = OD.purchase_order_id
                WHERE {filter}
                GROUP BY
                    U.id,
                    nombre_completo,
                    tipo_documento,
                    numero_documento,
                    email,
                    P.id,
                    P.nombre_evento,
                    P.fecha_inicio,
                    P.fecha_fin
                ORDER BY
                    P.fecha_inicio DESC 
        ";

        if ($criteria) {
            $sql = str_replace('{filter}', $criteria->getExpression(), $sql);
            $bindings = $criteria->getParameters();
        }
        // $sql .= $filter->getExpression();
        // $bindings = $filter->getParameters();

        $sql .= " LIMIT :offset, :perPage;";
        $bindings['offset'] = $offset;
        $bindings['perPage'] = $perPage;

        // echo '<pre>';
        // var_dump($sql);
        // var_dump($bindings);
        // echo '</pre>';


        $stmt = $builder->run($sql, $bindings);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        $totalPages = ceil($totalData / $perPage);
        if (!$result) {
            $result = [];
        }
        return [
            'data' => $result,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'totalData' => $totalData,
            'error' => $error
        ];
    }
}
