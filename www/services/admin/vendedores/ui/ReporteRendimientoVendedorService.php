<?php

namespace app\services\admin\vendedores\ui;

use app\helpers\ModelToUIHelper;
use app\helpers\RouteHelper;
use app\models\Document_type;
use app\services\ui\form\Form;
use app\services\ui\form\FormBuilder;
use app\services\ui\html\HtmlFactory;
use app\services\ui\paginator\Paginator;

class ReporteRendimientoVendedorService
{
    private $formBuilder;
    private $documentTypes;
    private $args;
    public function __construct() {}

    public function getTable($data)
    {
        // $strategy = new VendedoresStrategy();
        $strategy = new ReporteRendimientoVendedorStrategy();
        $columnNames = [
            ['id', '5%'], // usuario_id
            'nombre', // nombre_completo
            'email', // email
            'documento', // tipo_documento
            'número', // numero_documento
            'evento', // evento
            'inicio', // fecha_inicio_evento
            'fin', // fecha_fin_evento
            'total ventas', // total_ventas
            'productos vendidos', // cantidad_productos_vendidos
        ];
        return (new ReporteRendimientoVendedorTable($strategy, $data, $columnNames))->get();
    }

    public function getPaginator($result, $page)
    {
        return new Paginator(
            $page,
            RouteHelper::getUrlFor('reporteRendimientoVendedorView'),
            $result['currentPage'],
            $result['totalPages'],
            $result['perPage'],
            $result['totalData'],
        );
    }
}
