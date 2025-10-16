<?php

namespace app\models;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Role
 * 
 * This class represents the 'roles' table in the database.
 * 
 */
class Project_salespeople extends Model
{
    protected $table = 'project_salespeople';
    protected $fillable = ['users_id', 'projects_id', 'status'];

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
    public static function getTotalVendedoresDisponiblesByEvento(int $evento_id)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $sql = "SELECT
                    COUNT(*) AS total
                FROM
                    users u
                JOIN
                    roles r ON u.roles_id = r.id
                LEFT JOIN
                    project_salespeople ps ON u.id = ps.users_id AND ps.projects_id = $evento_id
                WHERE
                    r.name = 'vendedor'
                    AND ps.users_id IS NULL
                    AND u.status = 'activo'
                ORDER BY
                    u.primer_nombre, u.primer_apellido;
        ";

        // echo '<pre>';
        // var_dump($sql);
        // var_dump($bindings);
        // echo '</pre>';
        $stmt = $builder->run($sql);
        $result = $stmt->fetch();
        if ($result) {
            return $result->total;
        }
        return 0;
    }

    public static function getVendedoresDisponiblesByEvento(int $evento_id, int $page = 1, $fetccMode = \PDO::FETCH_OBJ)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $perPage = 10;
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $totalData = 0;
        $error = '';

        $totalData = self::getTotalVendedoresDisponiblesByEvento($evento_id);
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
                    u.id AS user_id,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email
                FROM
                    users u
                JOIN
                    roles r ON u.roles_id = r.id
                LEFT JOIN
                    project_salespeople ps ON u.id = ps.users_id AND ps.projects_id = $evento_id
                WHERE
                    r.name = 'vendedor'
                    AND ps.users_id IS NULL
                    AND u.status = 'activo'
                ORDER BY
                    u.primer_nombre, u.primer_apellido
        ";

        $sql .= " LIMIT :offset, :perPage;";
        $bindings['offset'] = $offset;
        $bindings['perPage'] = $perPage;

        // var_dump($sql);
        // echo '<br>';
        // var_dump($bindings);

        $stmt = $builder->run($sql, $bindings);
        $stmt->setFetchMode($fetccMode);
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

    public static function getTotalVendedoresSeleccionadosByEvento(int $evento_id)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $sql = "SELECT
                    COUNT(*) AS total
                FROM
                    project_salespeople ps
                JOIN
                    users u ON ps.users_id = u.id
                WHERE
                    ps.projects_id = $evento_id
                ORDER BY
                    u.primer_nombre, u.primer_apellido;
        ";

        // echo '<pre>';
        // var_dump($sql);
        // var_dump($bindings);
        // echo '</pre>';
        $stmt = $builder->run($sql);
        $result = $stmt->fetch();
        if ($result) {
            return $result->total;
        }
        return 0;
    }

    public static function getVendedoresSeleccionadosByEvento(int $evento_id, int $page = 1, $fetccMode = \PDO::FETCH_OBJ)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $perPage = 10;
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $totalData = 0;
        $error = '';

        $totalData = self::getTotalVendedoresSeleccionadosByEvento($evento_id);
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
                    ps.id AS project_salesperson_id,
                    u.id AS user_id,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    ps.status AS project_assignment_status,
                    CASE
                        WHEN EXISTS (
                            SELECT 1
                            FROM purchase_orders po
                            WHERE po.salesperson_id = u.id
                            AND po.project_id = ps.projects_id
                        ) THEN 'bloqueado'   
                        ELSE 'editable'
                    END AS vendor_status
                FROM
                    project_salespeople ps
                JOIN
                    users u ON ps.users_id = u.id
                WHERE
                    ps.projects_id = $evento_id  
                ORDER BY
                    u.primer_nombre, u.primer_apellido 
        ";

        $sql .= " LIMIT :offset, :perPage;";
        $bindings['offset'] = $offset;
        $bindings['perPage'] = $perPage;

        // var_dump($sql);
        // echo '<br>';
        // var_dump($bindings);

        $stmt = $builder->run($sql, $bindings);
        $stmt->setFetchMode($fetccMode);
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
