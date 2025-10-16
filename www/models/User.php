<?php

namespace app\models;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class User
 * 
 * This class represents the 'users' table in the database.
 * 
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['roles_id', 'document_type_id', 'email', 'password_hash', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'direccion', 'telefono', 'numero_identificacion', 'status'];

    const ACTIVO = 'activo';
    const INACTIVO = 'inactivo';

    public function __construct()
    {
        parent::__construct();
        // TODO: Crear reglas de negocio aca.
        // $this->rules->add(
        //     'email',
        //     (new Rules())
        //         ->unique($this->table, 'email')
        //         ->get()
        // );
        // $this->rules->add(
        //     'numero_identificacion',
        //     (new Rules())
        //         ->unique($this->table, 'numero_identificacion')
        //         ->get()
        // );
    }


    public function getCustomer()
    {
        if (!$this->id) return null;

        $this->builder->select('u.id AS user_id')
            ->select('c.id AS customer_id')
            ->select('r.name as role')
            ->select('u.email')
            ->select("CONCAT(u.primer_nombre, 
                IFNULL(CONCAT(' ', u.segundo_nombre), ''), 
                ' ', 
                u.primer_apellido, 
                IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo")
            ->select('u.direccion')
            ->select('u.telefono')
            ->select('u.numero_identificacion')
            ->from('customers c')
            ->join('users u', 'c.user_id', '=', 'u.id') // This line is corrected for consistency
            ->join('roles r', 'u.roles_id', '=', 'r.id')
            ->where('u.id', '=', $this->id);

        $stmt = $this->builder->get();
        try {
            $this->deactivateFetchObject();
            $result = $this->fetchResult($stmt);
            $this->activateFetchObject();
            return $result;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getTotalvendedores(?Criteria $criteria = null)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $sql = "SELECT
                    COUNT(*) AS total
                FROM
                    users u
                JOIN
                    roles r ON u.roles_id = r.id
                JOIN
                    document_type dt ON u.document_type_id = dt.id
                WHERE                     
        ";
        if ($criteria) {
            $sql .= $criteria->getExpression();
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
    public static function getVendedores(?Criteria $criteria = null, int $page = 1)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $perPage = 10;
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $totalData = 0;
        $error = '';

        $vendedorCriteria = Criteria::create('r.name', '=', 'vendedor');
        // $vendedorCriteria = Criteria::equals('r.name', 'administrador');
        if ($criteria) {
            $vendedorCriteria = $vendedorCriteria->and($criteria);
        }

        $totalData = self::getTotalvendedores($vendedorCriteria);
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
                    CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido, 
                    IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo,
                    r.name AS role_name,
                    u.email,
                    u.direccion,
                    u.telefono,
                    dt.nombre AS tipo_documento,
                    dt.codigo AS codigo_tipo_documento,
                    u.numero_identificacion,
                    u.status
                FROM
                    users u
                JOIN
                    roles r ON u.roles_id = r.id
                JOIN
                    document_type dt ON u.document_type_id = dt.id
                WHERE
        ";

        $sql .= $vendedorCriteria->getExpression();
        $bindings = $vendedorCriteria->getParameters();

        $sql .= " LIMIT :offset, :perPage;";
        $bindings['offset'] = $offset;
        $bindings['perPage'] = $perPage;

        // var_dump($sql);
        // echo '<br>';
        // var_dump($bindings);

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

    public function getEmploye()
    {
        if (!$this->id) return null;

        $this->builder->select('u.id AS user_id')
            ->select('r.name as role')
            ->select('u.email')
            ->select("CONCAT(u.primer_nombre, 
                IFNULL(CONCAT(' ', u.segundo_nombre), ''), 
                ' ', u.primer_apellido, 
                IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo")
            ->select('u.direccion')
            ->select('u.telefono')
            ->from('users u')
            ->join('roles r', 'u.roles_id', '=', 'r.id')
            ->join('employes e', 'e.user_id', '=', 'u.id')
            ->where('u.id', '=', $this->id);

        $stmt = $this->builder->get();
        try {
            $this->deactivateFetchObject();
            $result = $this->fetchResult($stmt);
            $this->activateFetchObject();
            return $result;
        } catch (\Exception $e) {
            return null;
        }
    }
    public static function getSalesPersonByUserId($user_id)
    {
        $builder = Application::$app->builder;
        $vendedorCriteria = Criteria::equals('u.id', $user_id);

        $sql = "SELECT
                    u.id AS user_id,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido, 
                    IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo,
                    r.name AS role_name,
                    u.email,
                    u.direccion,
                    u.telefono,
                    dt.id AS tipo_documento_id,
                    dt.nombre AS tipo_documento,
                    dt.codigo AS codigo_tipo_documento,
                    u.numero_identificacion,
                    u.status
                FROM
                    users u
                JOIN
                    roles r ON u.roles_id = r.id
                JOIN
                    document_type dt ON u.document_type_id = dt.id
                WHERE
        ";
        $sql .= $vendedorCriteria->getExpression();
        $bindings = $vendedorCriteria->getParameters();

        $stmt = $builder->run($sql, $bindings);
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        }
        return false;
    }
    public function role()
    {
        if (!$this->id) return null;
        $model = new Role();
        return $model->find($this->roles_id);
    }
    public function isCustomer()
    {
        if (!$this->id) return null;
        if ($this->role()->name === 'Administrador') {
            return false;
        }
        return true;
    }
}
