<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class User
 * 
 * This class represents the 'users' table in the database.
 * 
 */
class Customers_event extends Model
{
    protected $table = 'customers_events';
    protected $fillable = ['project_id', 'customer_id', 'status'];
    public function __construct()
    {
        parent::__construct();
    }

    public function countPicturesByCustomersEvents()
    {
        if (!$this->id) {
            return 0;
        }
        $this->builder->select('COUNT(*) as count')
            ->from('pictures')
            ->where('customers_events_id', '=', $this->id);

        $stmt = $this->builder->get();

        try {
            $this->deactivateFetchObject();
            $result = $this->fetchResult($stmt);
            $this->activateFetchObject();

            if ($result) {
                return (int)$result->count;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            error_log("Error al obtener el conteo de imagenes: " . $e->getMessage());
            return 0;
        }
    }
}
