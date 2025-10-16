<?php

namespace app\models;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Order_detail
 * 
 * This class represents the 'order_details' table in the database.
 * 
 */
class Order_detail extends Model
{
    protected $table = 'order_details';
    protected $fillable = ['purchase_order_id', 'service_id', 'cantidad', 'precio_unitario', 'subtotal'];

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
    /**
     * Listado de todos los servicios solicitados para un usuario en un proyecto
     * Esta consulta te proporcionará todos los order_details (detalles de orden) 
     * asociados a un usuario específico dentro de un proyecto en particular.
     * @param mixed $userId
     * @param mixed $projectId
     * @return array
     */
    public static function getByCustomerAndEvent($customerId, $eventId, $fetch = \PDO::FETCH_OBJ): array
    {
        $builder = Application::$app->builder;
        $sql = "SELECT
                    od.id,
                    s.image,
                    cg.nombre AS nombre_categoria,
                    s.nombre AS nombre_servicio,
                    s.descripcion AS descripcion_servicio,
                    od.subtotal AS precio_servicio,
                    po.estado_pago AS estado_pago_servicio,
                    od.purchase_order_id,
                    od.service_id,
                    od.cantidad,
                    od.precio_unitario,
                    CASE
                        WHEN EXISTS (
                            SELECT 1
                            FROM `selected_pictures` sp
                            WHERE sp.order_detail_id = od.id
                        ) THEN 'seleccionadas'
                        ELSE 'sin seleccionar'
                    END AS estado_seleccion_fotos
                FROM
                    `order_details` od
                JOIN
                    `purchase_orders` po ON od.purchase_order_id = po.id
                JOIN
                    `customers` c ON po.customer_id = c.id
                JOIN
                    `users` u ON c.user_id = u.id
                JOIN
                    `services` s ON od.service_id = s.id
                JOIN
                    `categorias` cg ON s.categoria_id = cg.id
                JOIN
                    `customers_events` ce ON c.id = ce.customer_id AND po.project_id = ce.project_id
                WHERE
                    c.id = :customerId AND ce.project_id = :eventId;";
        $params = ['customerId' => $customerId, 'eventId' => $eventId];
        $stmt = $builder->run($sql, $params);
        $stmt->setFetchMode($fetch);
        $result = $stmt->fetchAll();
        if ($result) {
            return $result;
        }
        return [];
    }

    public static function getServiceByOrderDetailId($order_detail_id)
    {
        $builder = Application::$app->builder;
        $sql = "SELECT
                s.*,
                od.*
            FROM
                order_details od
            JOIN
                services s ON od.service_id = s.id
            WHERE
                od.id = :order_detail_id;
        ";
        $params = ['order_detail_id' => $order_detail_id];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        }
        return null;
    }
    public static function getCustomerEventInfoByOrderDetailId($order_detail_id)
    {
        $builder = Application::$app->builder;
        $sql = "SELECT
                    od.id AS order_detail_id,
                    po.id AS purchase_order_id,
                    po.fecha_orden AS fecha_orden_compra,
                    po.total_bruto AS total_bruto_compra,
                    po.total_neto AS total_neto_compra,
                    po.estado_pago AS estado_pago_compra,
                    c.id AS customer_id,
                    c.notas AS notas_cliente,
                    u.id AS user_id,
                    u.email AS email_usuario,
                    CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido, 
                        IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo_usuario,
                    u.primer_nombre AS primer_nombre_usuario,
                    u.segundo_nombre AS segundo_nombre_usuario,
                    u.primer_apellido AS primer_apellido_usuario,
                    u.segundo_apellido AS segundo_apellido_usuario,
                    u.direccion AS direccion_usuario,
                    u.telefono AS telefono_usuario,
                    u.numero_identificacion AS numero_identificacion_usuario,
                    dt.id AS document_type_id,
                    dt.nombre AS nombre_tipo_documento,
                    dt.codigo AS codigo_tipo_documento,
                    r.id AS role_id,
                    r.name AS nombre_rol,
                    r.description AS descripcion_rol,
                    p.id AS project_id,
                    p.nombre_evento AS nombre_evento_proyecto,
                    p.fecha_inicio AS fecha_inicio_proyecto,
                    p.fecha_fin AS fecha_fin_proyecto,
                    p.status AS estado_proyecto,
                    p.descripcion AS descripcion_proyecto,
                    p.hora_ceremonia AS hora_ceremonia_proyecto,
                    i.id AS institution_id,
                    i.nombre AS nombre_institucion,
                    l.id AS location_id,
                    l.nombre AS nombre_ubicacion,
                    l.direccion AS direccion_ubicacion,
                    ce.id AS customer_event_id,
                    ce.status AS estado_cliente_evento,
                    ce.created_at AS creado_en_cliente_evento,
                    ce.updated_at AS actualizado_en_cliente_evento
                FROM
                    `order_details` od
                JOIN
                    `purchase_orders` po ON od.purchase_order_id = po.id
                JOIN
                    `customers` c ON po.customer_id = c.id
                JOIN
                    `users` u ON c.user_id = u.id
                JOIN
                    `document_type` dt ON u.document_type_id = dt.id
                JOIN
                    `roles` r ON u.roles_id = r.id
                JOIN
                    `projects` p ON po.project_id = p.id
                JOIN
                    `institutions` i ON p.institution_id = i.id
                JOIN
                    `locations` l ON p.location_id = l.id
                JOIN
                    `customers_events` ce ON c.id = ce.customer_id AND p.id = ce.project_id
                WHERE
                    od.id = :order_detail_id;
        ";
        $params = ['order_detail_id' => $order_detail_id];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        }
        return null;
    }
    public static function getAllOrderDetailInfo(?Criteria $criteria = null, int $page = 1)
    {
        $builder = Application::$app->builder;
        // Validar los parámetros de paginación
        $perPage = 10;
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;

        if ($criteria === null) {
            $criteria = Criteria::equals('p.status', 'activo')
                ->and(Criteria::notEquals('od.dispatch_status', 'entregado'));
        }

        $totalData = self::getTotalOrderDetailCount($criteria);
        $totalPages = ceil($totalData / $perPage);

        $error = '';
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
                    od.id AS order_detail_id,
                    po.id AS purchase_order_id,
                    u.id AS user_id,
                    c.id AS customer_id,
                    p.id AS project_id,
                    s.id AS service_id,
                    ce.id AS customer_event_id,
                    s.nombre AS nombre_servicio,
                    s.max_fotos AS max_fotos_servicio,
                    s.image AS imagen_servicio,
                    cg.nombre AS nombre_categoria,
                    po.fecha_orden AS fecha_orden_compra,
                    po.total_bruto AS total_bruto_compra,
                    po.total_neto AS total_neto_compra,
                    u.email AS email_usuario,
                    u.primer_nombre AS primer_nombre_usuario,
                    u.segundo_nombre AS segundo_nombre_usuario,
                    u.primer_apellido AS primer_apellido_usuario,
                    u.segundo_apellido AS segundo_apellido_usuario,
                    CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido, 
                        IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo_usuario,
                    u.direccion AS direccion_usuario,
                    u.telefono AS telefono_usuario,
                    u.numero_identificacion AS numero_identificacion_usuario,
                    dt.nombre AS nombre_tipo_documento,
                    dt.codigo AS codigo_tipo_documento,
                    r.name AS nombre_rol,
                    p.nombre_evento AS nombre_evento_proyecto,
                    p.fecha_inicio AS fecha_inicio_proyecto,
                    p.fecha_fin AS fecha_fin_proyecto,
                    p.hora_ceremonia AS hora_ceremonia_proyecto,
                    i.nombre AS nombre_institucion,
                    l.nombre AS nombre_ubicacion,
                    l.direccion AS direccion_ubicacion,
                    ce.created_at AS fecha_creacion_cliente_evento,
                    ce.updated_at AS fecha_actualizacion_cliente_evento,
                    p.status AS estado_proyecto,
                    ce.status AS estado_cliente_evento,
                    po.estado_pago AS estado_pago_compra,
                    od.dispatch_status AS status_entrega,
                    CASE
                        WHEN EXISTS (
                            SELECT 1
                            FROM `selected_pictures` sp
                            WHERE sp.order_detail_id = od.id
                        ) THEN 'seleccionadas'
                        ELSE 'sin seleccionar'
                    END AS estado_seleccion_fotos
                FROM
                    `order_details` od
                JOIN
                    `purchase_orders` po ON od.purchase_order_id = po.id
                JOIN 
                    `services` s ON od.service_id = s.id
                JOIN
                    `categorias` cg ON cg.id = s.categoria_id
                JOIN
                    `customers` c ON po.customer_id = c.id
                JOIN
                    `users` u ON c.user_id = u.id
                JOIN
                    `document_type` dt ON u.document_type_id = dt.id
                JOIN
                    `roles` r ON u.roles_id = r.id
                JOIN
                    `projects` p ON po.project_id = p.id
                JOIN
                    `institutions` i ON p.institution_id = i.id
                JOIN
                    `locations` l ON p.location_id = l.id
                JOIN
                    `customers_events` ce ON c.id = ce.customer_id AND p.id = ce.project_id
                WHERE ";
        $params = [];
        if ($criteria !== null) {
            $sql .= $criteria->getExpression();
            $params = $criteria->getParameters();
        }
        $sql .= " LIMIT :offset, :perPage;";
        $params['offset'] = $offset;
        $params['perPage'] = $perPage;

        // echo 'Query:' . $sql . '<br>Parametros:';
        // var_dump($params);
        // echo '</br>';

        $stmt = $builder->run($sql, $params);
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
    public static function getTotalOrderDetailCount(?Criteria $criteria = null)
    {
        $builder = Application::$app->builder;
        // Validar los parámetros de paginación
        $sql = "SELECT
                    COUNT(*) AS total
                FROM
                    `order_details` od
                JOIN
                    `purchase_orders` po ON od.purchase_order_id = po.id
                JOIN 
                    `services` s ON od.service_id = s.id
                JOIN
                    `customers` c ON po.customer_id = c.id
                JOIN
                    `users` u ON c.user_id = u.id
                JOIN
                    `document_type` dt ON u.document_type_id = dt.id
                JOIN
                    `roles` r ON u.roles_id = r.id
                JOIN
                    `projects` p ON po.project_id = p.id
                JOIN
                    `institutions` i ON p.institution_id = i.id
                JOIN
                    `locations` l ON p.location_id = l.id
                JOIN
                    `customers_events` ce ON c.id = ce.customer_id AND p.id = ce.project_id
                WHERE ";
        $params = [];
        if ($criteria) {
            $sql .= $criteria->getExpression();
            $params = $criteria->getParameters();
        }
        // echo 'Query:' . $sql . '<br>Parametros:';
        // var_dump($params);
        // echo '</br>';
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result->total;
        }
        return 0;
    }

    public static function getMaxNumImagesAllowed($order_detail_id)
    {
        $builder = Application::$app->builder;
        $sql = "SELECT s.max_fotos AS numero_imagenes_servicio
                FROM order_details od
                JOIN services s ON od.service_id = s.id
                WHERE od.id = :order_detail_id;
        ";
        $params = ['order_detail_id' => $order_detail_id];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result->numero_imagenes_servicio;
        }
        return 0;
    }
    /**
     * Listado de todos los productos pagados para un usuario en un proyecto:
     * Esta consulta es similar a la anterior, pero añade un filtro para seleccionar solo
     * los detalles de orden asociadosaquellos order_details cuyas órdenes de compra 
     * (purchase_orders) tengan un estado_pago igual a 'pagado'.
     * @param mixed $userId
     * @param mixed $projectId
     * @return void
     */
    public static function getPaidByClientAndEvent($userId, $projectId) {}
    /**
     * Listado de todos los productos adeudados para un usuario en un proyecto
     * Similar a la consulta de productos pagados, pero en este caso filtramos 
     * por estado_pago que sea diferente de 'pagado'.  Consideraremos 'pendiente' 
     * como estado de "adeudado". Podrías incluir otros estados según tu lógica de 
     * negocio (como 'cancelado' si también se considera adeudado en algún contexto).
     * @param mixed $userId
     * @param mixed $projectId
     * @return void
     */
    public static function getUnpaidByClientAndEvent($userId, $projectId) {}
}
