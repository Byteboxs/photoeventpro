<?php

namespace app\models;

use app\core\Application;
use app\core\database\builder\Criteria;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Customer
 * 
 * This class represents the 'customers' table in the database.
 * 
 */
class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = ['user_id', 'notas'];

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

    public function getUser()
    {
        if (!$this->id) return null;

        $this->builder->select('u.id AS user_id')
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
            ->where('c.id', '=', $this->id);

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

    public static function getServicesInActiveProjectByUserId($user_id)
    {
        $builder = Application::$app->builder;
        $sql = "SELECT od.id AS order_detail_id,
            cg.nombre AS nombre_categoria,
            s.nombre AS nombre_servicio,
            s.descripcion AS descripcion_servicio,
            s.image,
            od.cantidad AS cantidad_servicio,
            od.precio_unitario AS precio_unitario_servicio,
            po.estado_pago AS estado_pago_servicio,
            COUNT(sp.picture_id) AS numero_fotos_seleccionadas,
            s.max_fotos AS numero_total_fotos_servicio,
            CASE
                WHEN COUNT(sp.picture_id) < s.max_fotos AND po.estado_pago = 'pagado' THEN 'edicion'
                WHEN COUNT(sp.picture_id) = s.max_fotos AND po.estado_pago = 'pagado' THEN 'lectura'
                ELSE 'inactivo'
            END AS estado_servicio
        FROM
            users u
        JOIN
            customers c ON u.id = c.user_id
        JOIN
            customers_events ce ON c.id = ce.customer_id
        JOIN
            projects p ON ce.project_id = p.id
        JOIN
            purchase_orders po ON c.id = po.customer_id AND p.id = po.project_id
        JOIN
            order_details od ON po.id = od.purchase_order_id
        JOIN
            services s ON od.service_id = s.id
        JOIN categorias cg ON s.categoria_id = cg.id
        LEFT JOIN
            selected_pictures sp ON od.id = sp.order_detail_id
        WHERE
            u.id = :user_id
            AND p.status = 'activo'
        GROUP BY
            od.id, s.id, po.estado_pago, s.max_fotos;
        ";
        $params = ['user_id' => $user_id];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetchAll();
        if ($result) {
            return $result;
        }
        return null;
    }

    public static function getImagenesProyectoActivoByUserId($userId)
    {
        $builder = Application::$app->builder;
        $sql = "SELECT p.id AS picture_id,p.file_path AS original_path,
                    REPLACE(p.file_path, SUBSTRING_INDEX(p.file_path, '/', -1), CONCAT('thumbnails/', SUBSTRING_INDEX(p.file_path, '/', -1))) AS thumbnails_path
                FROM
                    users u
                JOIN
                    customers c ON u.id = c.user_id
                JOIN
                    customers_events ce ON c.id = ce.customer_id
                JOIN
                    projects pj ON ce.project_id = pj.id
                JOIN
                    pictures p ON ce.id = p.customers_events_id
                WHERE
                    u.id = :user_id
                    AND pj.status = 'activo';
        ";
        $params = ['user_id' => $userId];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetchAll();
        if ($result) {
            return $result;
        }
        return null;
    }
    public static function getAdvancedInformationByUserId($user_id)
    {
        $builder = Application::$app->builder;
        $sql = "SELECT
                    u.id AS user_id,
                    u.email,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    CONCAT(u.primer_nombre, IFNULL(CONCAT(' ', u.segundo_nombre), ''), ' ', u.primer_apellido, IFNULL(CONCAT(' ', u.segundo_apellido), '')) AS nombre_completo,
                    u.direccion,
                    u.telefono,
                    u.numero_identificacion,
                    c.id AS customer_id,
                    c.notas AS customer_notes,
                    bi.id AS billing_info_id,
                    bi.tipo_persona AS billing_tipo_persona,
                    bi.razon_social AS billing_razon_social,
                    bi.nit AS billing_nit,
                    bi.direccion_facturacion AS billing_direccion_facturacion,
                    si.id AS shipping_info_id,
                    si.nombre_contacto AS shipping_nombre_contacto,
                    si.direccion_envio AS shipping_direccion_envio,
                    si.telefono_contacto AS shipping_telefono_contacto,
                    si.instrucciones_adicionales AS shipping_instrucciones_adicionales,
                    p.id AS proyecto_id,
                    p.nombre_evento AS proyecto_nombre,
                    p.fecha_inicio AS proyecto_fecha_inicio,
                    p.fecha_fin AS proyecto_fecha_fin,
                    (SELECT COUNT(DISTINCT od.service_id)
                    FROM purchase_orders po
                    JOIN order_details od ON po.id = od.purchase_order_id
                    WHERE po.customer_id = c.id AND po.project_id = p.id) AS numero_servicios_seleccionados
                FROM
                    users u
                JOIN
                    customers c ON u.id = c.user_id
                LEFT JOIN
                    billing_information bi ON c.id = bi.customer_id
                LEFT JOIN
                    shipping_information si ON c.id = si.customer_id
                LEFT JOIN
                    customers_events ce ON c.id = ce.customer_id
                LEFT JOIN
                    projects p ON ce.project_id = p.id AND p.status = 'activo'
                WHERE
                    u.id = :user_id;
            ";
        $params = ['user_id' => $user_id];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        }
        return null;
    }

    public static function findCustomerBy(Criteria $criteria)
    {
        $logger = Application::$app->logger;
        $builder = Application::$app->builder;
        $sql = 'SELECT
                    c.id AS customer_id,
                    u.id AS user_id,
                    u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.segundo_apellido,
                    CONCAT(u.primer_nombre, " ", u.primer_apellido) AS nombre_completo,
                    dt.nombre AS tipo_documento,
                    u.numero_identificacion AS numero_documento,
                    u.email,
                    si.nombre_contacto,
                    si.telefono_contacto,
                    u.direccion AS direccion_residencia,
                    si.direccion_envio,
                    u.telefono AS telefono_usuario
                FROM customers c
                JOIN users u ON c.user_id = u.id 
                JOIN document_type dt ON u.document_type_id = dt.id
                LEFT JOIN shipping_information si ON c.id = si.customer_id
                WHERE 
        ';
        $sql .= $criteria->getExpression();
        $bindings = $criteria->getParameters();
        $logger->log('Log consulta findCustomerBy: ', $sql);
        $logger->log('Log consulta findCustomerBy bindings: ', print_r($bindings, true));
        $stmt = $builder->run($sql, $bindings);
        $result = $stmt->fetch();
        if (!$result) {
            $result = null;
        }
        return $result;
    }

    public static function getBasicInformationByCustomerId($customer_id)
    {
        $builder = Application::$app->builder;
        $sql = 'SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.segundo_apellido,
                    dt.nombre AS tipo_documento,
                    u.numero_identificacion AS numero_documento,
                    u.email AS email_usuario,
                    si.nombre_contacto,
                    si.telefono_contacto,
                    u.direccion AS direccion_residencia,
                    si.direccion_envio,
                    u.telefono AS telefono_usuario
                FROM customers c
                JOIN users u ON c.user_id = u.id 
                JOIN document_type dt ON u.document_type_id = dt.id
                LEFT JOIN shipping_information si ON c.id = si.customer_id
                WHERE c.id = :customer_id;
        ';
        $params = ['customer_id' => $customer_id];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetch();
        if ($result) {
            return $result;
        }
        return null;
    }

    public static function getShippingInformationByCustomerId($customer_id) {}
}
