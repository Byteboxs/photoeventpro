<?php

namespace app\models;

use app\core\Application;
use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Selected_picture
 * 
 * This class represents the 'selected_pictures' table in the database.
 * 
 */
class Selected_picture extends Model
{
    protected $table = 'selected_pictures';
    protected $fillable = ['picture_id', 'order_detail_id', 'order_index'];

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

    public static function getPicturesByOrderDetailId($order_detail_id)
    {
        $builder = Application::$app->builder;
        $sql = "SELECT sp.id AS selected_picture_id, p.id AS picture_id, sp.order_index,p.file_path AS image_file,
            REPLACE(p.file_path, SUBSTRING_INDEX(p.file_path, '/', -1), CONCAT('thumbnails/', SUBSTRING_INDEX(p.file_path, '/', -1))) AS thumbnail_file
            FROM
                selected_pictures sp
            JOIN
                pictures p ON sp.picture_id = p.id
            WHERE
                sp.order_detail_id = :order_detail_id
            ORDER BY
                sp.order_index ASC
        ";
        $params = ['order_detail_id' => $order_detail_id];
        $stmt = $builder->run($sql, $params);
        $result = $stmt->fetchAll();
        if ($result) {
            return $result;
        }
        return [];
    }
}
