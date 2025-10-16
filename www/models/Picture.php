<?php

namespace app\models;

use app\core\model\Model;
use app\core\model\Rules;

/**
 * Class Picture
 * 
 * This class represents the 'pictures' table in the database.
 * 
 */
class Picture extends Model
{
    protected $table = 'pictures';
    protected $fillable = ['customers_events_id', 'file_path', 'fecha_captura', 'status'];

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

    public function findAllByCustomersEventsId($customerEventId)
    {
        $this->builder->select('p.*')
            ->from('pictures p')
            ->where('p.customers_events_id', '=', $customerEventId);
        $stmt = $this->builder->get();
        try {
            $this->deactivateFetchObject();
            $result = $this->fetchAllResult($stmt);
            $this->activateFetchObject();
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }
}
