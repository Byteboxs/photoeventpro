<?php

namespace app\models;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Project
 * 
 * This class represents the 'projects' table in the database.
 * 
 */
class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = [
        'institution_id',
        // 'pricing_plans_id',
        'location_id',
        'nombre_evento',
        'fecha_inicio',
        'fecha_fin',
        'hora_ceremonia',
        'status',
        'descripcion'
    ];
    const STATUS = [
        'PROGRAMADO' => 'programado',
        'ACTIVO' => 'activo',
        'FINALIZADO' => 'finalizado',
        'CANCELADO' => 'cancelado'
    ];

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
    public function getTotalProjectCount(): int
    {
        $this->builder->select('COUNT(*) as total')
            ->from('projects');

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
            error_log("Error al obtener el conteo de proyectos: " . $e->getMessage());
            return 0;
        }
    }

    public function getProjects(int $page = 1)
    {
        // Validar los parámetros de paginación
        $page = max(1, $page); // Asegurar que la página sea al menos 1
        $this->perPage = max(1, $this->perPage); // Asegurar que los elementos por página sean al menos 1

        $offset = ($page - 1) * $this->perPage;
        $totalData = $this->getTotalProjectCount();
        if (!$totalData) {
            return [
                'data' => [],
                'currentPage' => $page,
                'totalPages' => 0,
                'perPage' => $this->perPage,
                'totalData' => 0
            ];
        }

        $this->builder->select('p.id AS project_id')
            ->select('p.nombre_evento AS nombre')
            ->select('p.fecha_inicio AS inicio')
            ->select('p.fecha_fin AS fin')
            ->select('p.hora_ceremonia AS hora_ceremonia')
            ->select('p.status AS estado')
            ->select('p.descripcion AS descripcion')
            ->select('i.nombre AS institucion')
            ->select('l.nombre AS ubicacion')
            ->select('l.direccion AS direccion')
            ->from('projects p')
            ->join('institutions i', 'p.institution_id', '=', 'i.id')
            ->join('locations l', 'p.location_id', '=', 'l.id')
            ->limitOffset($this->perPage, $offset);

        $stmt = $this->builder->get();

        try {
            $this->deactivateFetchObject();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $data = $this->fetch($stmt);

            $totalPages = ceil($totalData / $this->perPage);

            return [
                'data' => $data,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $this->perPage,
                'totalData' => $totalData
            ];
        } catch (\Exception $e) {
            error_log("Error al obtener proyectos paginados: " . $e->getMessage());
            return null;
        }
    }

    public function getTotalCustomerCount()
    {
        $this->builder->select('COUNT(*) as total')
            ->from('customers')
            ->join('customers_events ce', 'customers.id', '=', 'ce.customer_id')
            ->where('ce.project_id', '=', $this->id);
        // ->from('customers c')
        // ->join('customers_events ce', 'c.id', '=', 'ce.customer_id')
        // ->join('users u', 'u.id', '=', 'c.user_id')
        // ->join('document_type dt', 'dt.id', '=', 'u.document_type_id')
        // // Joins adicionales para order_details y purchase_orders - JOIN por customer_id y project_id individualmente
        // ->leftJoin('purchase_orders po', 'po.customer_id', '=', 'c.id') // JOIN por customer_id primero
        // ->leftJoin('order_details od', 'od.purchase_order_id', '=', 'po.id')
        // ->where('ce.project_id', '=', $this->id)
        // ->groupBy('c.id');

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
            error_log("Error al obtener el conteo de clientes: " . $e->getMessage());
            return 0;
        }
    }

    public function getCustomers(int $page = 1)
    {
        // Validar los parámetros de paginación
        $page = max(1, $page);
        $this->perPage = max(1, $this->perPage);
        $offset = ($page - 1) * $this->perPage;

        $totalData = $this->getTotalCustomerCount();
        if (!$totalData) {
            return [
                'data' => [],
                'currentPage' => $page,
                'totalPages' => 0,
                'perPage' => $this->perPage,
                'totalData' => 0
            ];
        }
        $this->builder->select('c.id AS customer_id')
            ->select('u.email')
            ->select('u.primer_nombre')
            ->select('u.segundo_nombre')
            ->select('u.primer_apellido')
            ->select('u.segundo_apellido')
            ->select("CONCAT(u.primer_nombre,
                            IFNULL(CONCAT(' ', u.segundo_nombre), ''),
                            ' ',
                            u.primer_apellido,
                            IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo")
            ->select('u.status AS estado')
            // Agregaciones sin subconsulta, usando CASE WHEN y SUM/COUNT
            ->select('COUNT(DISTINCT od.id) AS servicios_seleccionados') // Cuenta order_details distintos
            ->select("SUM(CASE WHEN po.estado_pago = 'pendiente' AND po.project_id = ce.project_id THEN od.cantidad ELSE 0 END) AS servicios_sin_pagar")
            ->select("SUM(CASE WHEN po.estado_pago = 'pagado' AND po.project_id = ce.project_id THEN od.cantidad ELSE 0 END) AS servicios_pagados")
            ->select('u.direccion')
            ->select('u.telefono')
            ->select('dt.nombre as document_type')
            ->select('u.numero_identificacion')
            ->select('c.notas')
            ->from('customers c')
            ->join('customers_events ce', 'c.id', '=', 'ce.customer_id')
            ->join('users u', 'u.id', '=', 'c.user_id')
            ->join('document_type dt', 'dt.id', '=', 'u.document_type_id')
            // Joins adicionales para order_details y purchase_orders - JOIN por customer_id y project_id individualmente
            ->leftJoin('purchase_orders po', 'po.customer_id', '=', 'c.id') // JOIN por customer_id primero
            ->leftJoin('order_details od', 'od.purchase_order_id', '=', 'po.id')


            ->where('ce.project_id', '=', $this->id)
            ->groupBy('c.id')
            // Agrupar por customer_id para las funciones de agregación
            // ->orderBy('ce.created_at', 'ASC')
            ->limitOffset($this->perPage, $offset);

        $stmt = $this->builder->get();

        try {
            $this->deactivateFetchObject();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $data = $this->fetch($stmt);

            $totalPages = ceil($totalData / $this->perPage);

            return [
                'data' => $data,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'perPage' => $this->perPage,
                'totalData' => $totalData
            ];
        } catch (\Exception $e) {
            error_log("Error al obtener clientes paginados: " . $e->getMessage());
            return null;
        }
    }

    // public function getCustomers(int $page = 1)
    // {
    //     // Validar los parámetros de paginación
    //     $page = max(1, $page); // Asegurar que la página sea al menos 1
    //     $this->perPage = max(1, $this->perPage); // Asegurar que los elementos por página sean al menos 1
    //     $offset = ($page - 1) * $this->perPage;

    //     $totalData = $this->getTotalCustomerCount();
    //     if (!$totalData) {
    //         return [
    //             'data' => [],
    //             'currentPage' => $page,
    //             'totalPages' => 0,
    //             'perPage' => $this->perPage,
    //             'totalData' => 0
    //         ];
    //     }
    //     $this->builder->select('c.id AS customer_id')
    //         ->select('u.email')
    //         ->select('u.primer_nombre')
    //         ->select('u.segundo_nombre')
    //         ->select('u.primer_apellido')
    //         ->select('u.segundo_apellido')
    //         ->select("CONCAT(u.primer_nombre, 
    //             IFNULL(CONCAT(' ', u.segundo_nombre), ''), 
    //             ' ', 
    //             u.primer_apellido, 
    //             IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo")
    //         ->select('u.status AS estado')
    //         ->select('u.direccion')
    //         ->select('u.telefono')
    //         ->select('dt.nombre as document_type')
    //         ->select('u.numero_identificacion')
    //         ->select('c.notas')
    //         ->from('customers c')
    //         ->join('customers_events ce', 'c.id', '=', 'ce.customer_id')
    //         ->join('users u', 'u.id', '=', 'c.user_id')
    //         ->join('document_type dt', 'dt.id', '=', 'u.document_type_id')
    //         ->where('ce.project_id', '=', $this->id)
    //         ->orderBy('ce.created_at', 'ASC')
    //         ->limitOffset($this->perPage, $offset);

    //     $stmt = $this->builder->get();

    //     try {
    //         $this->deactivateFetchObject();
    //         $stmt->setFetchMode(\PDO::FETCH_ASSOC);
    //         $data = $this->fetch($stmt);

    //         $totalPages = ceil($totalData / $this->perPage);

    //         return [
    //             'data' => $data,
    //             'currentPage' => $page,
    //             'totalPages' => $totalPages,
    //             'perPage' => $this->perPage,
    //             'totalData' => $totalData
    //         ];
    //     } catch (\Exception $e) {
    //         error_log("Error al obtener clientes paginados: " . $e->getMessage());
    //         return null;
    //     }
    // }
    public function getAllCustomers()
    {
        $this->builder->select('c.id AS customer_id')
            ->select('u.email')
            ->select('u.primer_nombre')
            ->select('u.segundo_nombre')
            ->select('u.primer_apellido')
            ->select('u.segundo_apellido')
            ->select("CONCAT(u.primer_nombre, 
                IFNULL(CONCAT(' ', u.segundo_nombre), ''), 
                ' ', 
                u.primer_apellido, 
                IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo")
            ->select('u.status AS estado')
            ->select('u.direccion')
            ->select('u.telefono')
            ->select('dt.nombre as document_type')
            ->select('u.numero_identificacion')
            ->select('c.notas')
            ->from('customers c')
            ->join('customers_events ce', 'c.id', '=', 'ce.customer_id')
            ->join('users u', 'u.id', '=', 'c.user_id')
            ->join('document_type dt', 'dt.id', '=', 'u.document_type_id')
            ->where('ce.project_id', '=', $this->id)
            ->orderBy('ce.created_at', 'ASC');

        $stmt = $this->builder->get();

        try {
            $this->deactivateFetchObject();
            $result = $this->fetchAllResult($stmt);
            $this->activateFetchObject();
            return $result;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getProjectData()
    {
        $this->builder->select('p.id AS project_id')
            ->select('p.nombre_evento AS nombre')
            ->select('p.fecha_inicio AS inicio')
            ->select('p.fecha_fin AS fin')
            ->select('p.hora_ceremonia AS hora_ceremonia')
            ->select('p.status AS estado')
            ->select('p.descripcion AS descripcion')
            ->select('i.nombre AS institucion')
            ->select('l.nombre AS ubicacion')
            ->select('l.direccion AS direccion')
            ->from('projects p')
            ->join('institutions i', 'p.institution_id', '=', 'i.id')
            ->join('locations l', 'p.location_id', '=', 'l.id')
            ->where('p.id', '=', $this->id);
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

    public function getCustomerEventByUserAndProject(int $userId, int $projectId)
    {
        $model = new Customers_event();
        return $model->findWhere([
            'project_id' => $projectId,
            'customer_id' => $userId
        ]);
    }

    public function hasUploadedPicturesForUserInProject(int $customerId): bool
    {
        // Si existe el attributo id el proyecto existe
        if (!$this->id) {
            return false;
        }
        // Verificar si el proyecto esta activo
        if ($this->status !== 'activo') {
            return false;
        }
        $customerEvent = $this->getCustomerEventByUserAndProject($customerId, $this->id);
        if (!$customerEvent) {
            return false; // El usuario no es cliente en este proyecto
        }

        // 3. Buscar imágenes cargadas
        $picturesCount = $customerEvent->countPicturesByCustomersEvents();
        if ($picturesCount > 0) {
            return true; // El usuario tiene imágenes cargadas en este proyecto
        }

        return false; // No se encontraron imágenes cargadas
    }

    public function getAllPicturesForCustomerInProject(int $customerId)
    {
        // Si existe el attributo id el proyecto existe
        if (!$this->id) {
            return [];
        }
        // Verificar si el proyecto esta activo
        if ($this->status !== 'activo') {
            return [];
        }
        // 1. Get the Customers_event record linking customer and project
        $customerEvent = $this->getCustomerEventByUserAndProject($customerId, $this->id);
        if (!$customerEvent) {
            return []; // Return empty array if no customer_event found for this customer in this project
        }

        // 2. Get all pictures associated with the customers_event_id
        $model = new Picture(); // Assuming you have a Picture model
        return $model->findAllByCustomersEventsId($customerEvent->id);
    }
    public static function getNumberOfNewActiveProjects(): int
    {
        $builder = Application::$app->builder;
        $sql = "SELECT COUNT(*) AS numero_nuevos_proyectos_activos
                FROM `projects`
                WHERE
                    `status` = 'activo'
                    AND `created_at` >= DATE_SUB(NOW(), INTERVAL 8 DAY);
        ";
        $params = [];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result->numero_nuevos_proyectos_activos;
        }
        return 0;
    }

    public static function getTotalProjectsForSalesperson(Criteria $criteria)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $sql = "SELECT
                    COUNT(*) AS total
                FROM
                    projects p
                JOIN
                    project_salespeople ps ON p.id = ps.projects_id
                JOIN
                    users u ON ps.users_id = u.id
                JOIN
                    institutions i ON p.institution_id = i.id
                JOIN
                    locations l ON p.location_id = l.id
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
    public static function getProjectsForSalesperson(Criteria $criteria, int $page = 1)
    {
        $builder = Application::$app->builder;
        $bindings = [];
        $perPage = 10;
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $totalData = 0;
        $error = '';

        $filter = Criteria::equals('p.status', 'activo')
            // ->or(Criteria::equals('ps.status', 'programado'))
            ->and(
                Criteria::equals('ps.status', 'activo')
                    ->and($criteria)
            );

        $totalData = self::getTotalProjectsForSalesperson($filter);
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
                    p.id AS project_id,
                    p.nombre_evento AS project_name,
                    p.fecha_inicio AS start_date,
                    p.fecha_fin AS end_date,
                    p.hora_ceremonia AS ceremony_time,
                    p.status AS project_status, 
                    i.nombre AS institution_name,
                    l.nombre AS location_name,
                    CASE
                        WHEN p.hora_ceremonia IS NOT NULL AND
                            CURRENT_TIMESTAMP BETWEEN
                            (TIMESTAMP(p.fecha_inicio, p.hora_ceremonia) - INTERVAL 12 HOUR) AND
                            (TIMESTAMP(p.fecha_inicio, p.hora_ceremonia) + INTERVAL 12 HOUR)
                        THEN 1
                        ELSE 0
                    END AS is_habilitado,
                    CASE
                        WHEN p.hora_ceremonia IS NOT NULL
                        THEN TIMESTAMP(p.fecha_inicio, p.hora_ceremonia) - INTERVAL 12 HOUR
                        ELSE NULL
                    END AS habilitation_start_time,
                    CASE
                        WHEN p.hora_ceremonia IS NOT NULL
                        THEN TIMESTAMP(p.fecha_inicio, p.hora_ceremonia) + INTERVAL 12 HOUR
                        ELSE NULL
                    END AS habilitation_end_time
                FROM
                    projects p
                JOIN
                    project_salespeople ps ON p.id = ps.projects_id
                JOIN
                    users u ON ps.users_id = u.id
                JOIN
                    institutions i ON p.institution_id = i.id
                JOIN
                    locations l ON p.location_id = l.id
                WHERE
                    
        ";

        $sql .= $filter->getExpression();
        $bindings = $filter->getParameters();

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

    public static function getProjectDataById($id)
    {
        $builder = Application::$app->builder;

        $sql = "SELECT p.id AS project_id, i.id AS institution_id, l.id AS location_id,
                    p.nombre_evento AS nombre, p.fecha_inicio AS inicio, p.fecha_fin AS fin,
                    p.hora_ceremonia AS hora_ceremonia, p.status AS estado, p.descripcion AS descripcion, 
                    i.nombre AS institucion, l.nombre AS ubicacion, l.direccion AS direccion
                FROM projects p
                JOIN institutions i ON p.institution_id = i.id
                JOIN locations l ON p.location_id = l.id
                WHERE p.id = :id
        ";
        $params = [
            'id' => $id
        ];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        }
        return null;
    }
}
