<?php

namespace app\controllers\admin\clientes;

use app\core\http\Request;
use app\models\Role;

class ClientesController
{
    private $contentView;
    public function __construct() {}

    //Registrar un cliente desde cero
    public function formularioRegistroClienteView(Request $request)
    {
        (new UIFormCrearClienteController())->draw($request);
    }
    // Listado de todos los clientes
    public function listaClientesView(Request $request)
    {
        (new UIListadoClientesController())->draw($request);
    }

    public function formularioAgregarClienteProyectoView(...$args)
    {
        (new UIFormAgregarClienteProyectoController())->draw(...$args);
    }

    // Accion de registrar un cliente en el sistema y relacionarlo con un proyecto
    public function registroClienteEventoAction(...$request)
    {
        (new ActionRegistrarClienteEventoController($request))->run();
    }
}
