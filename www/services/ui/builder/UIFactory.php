<?php

namespace app\services\ui\builder;

use app\core\views\View;
use app\services\ui\breadcrumb\BreadcrumbBuilderService;
use app\services\ui\form\FormBuilder;
use app\services\ui\table\BootstrapTable;
use app\services\ui\table\BS5ProductoCard;
use app\services\ui\table\TableConfig;
use app\services\ui\table\TableHeader;

class UIFactory
{

    public static function createMenu(string $role): UIMenuInterface
    {
        return match ($role) {
            'Administrador' => new UIAdminMenu(),
            'Veterinario' => new UIVeterinarioMenu(),
            'Asistente de laboratorio' => new UIAsistenteMenu(),
            'Cliente' => new UIClienteMenu(),
            default => new UIDefaultMenu(),
        };
    }

    public static function createSidebar(UIMenuInterface $menu, string $logo, string $avatar, $nombreUsuario): View
    {
        $sidebar = new View('dashboard.sidebar', ['nombre' => 'BancoSangreAnimal']);
        $sidebar->with('logo', $logo);
        $sidebar->with('avatar', $avatar);
        $sidebar->with('menu', $menu);
        $sidebar->with('nombreUsuario', $nombreUsuario);
        return $sidebar;
    }

    public static function createContentHeader(string $title, BreadcrumbBuilderService $breadcrumb): View
    {
        $contentHeader = new View('dashboard.contentheader');
        $contentHeader->with('title', $title);
        $contentHeader->with('breadcrumb', $breadcrumb);

        return $contentHeader;
    }

    public static function getDasboardView(string $role)
    {
        return match ($role) {
            'Administrador' => new View('dashboard.admindashboard'),
            'Veterinario' => new View('dashboard.veterinariodashboard'),
            'Asistente de laboratorio' => new View('dashboard.asistenteUI'),
            default => new View('dashboard.clientedashboard')
        };
    }

    public static function getDasboardByRole(string $role): string
    {
        return match ($role) {
            'Administrador' => 'dashboard.admindashboard',
            'Veterinario' => 'dashboard.veterinariodashboard',
            'Asistente de laboratorio' => 'dashboard.asistenteUI',
            default => 'dashboard.clientedashboard'
        };
    }

    public static function createTable(array $data, TableHeader $headers, string $tableId = 'table'): BootstrapTable
    {
        $tableConfig = new TableConfig();
        $tableConfig->setTableClasses('table table-dark');
        $tableConfig->setAttribute('id', $tableId);
        $table = new BootstrapTable($tableConfig, $data);
        $table->setHeader($headers);
        return $table;
    }

    public static function createProductoCardTable(array $data)
    {
        $html = '';
        foreach ($data as $key => $value) {
            $card = new BS5ProductoCard();
            $card->create([
                'image' => 'https://dummyimage.com/640x480/E63946/fff',
                'alt' => '',
                'title' => $value['nombre'],
                'description' => $value['description'],
                'tipoSangre' => $value['tipo_sangre'],
                'precio' => 'COP $ ' . round($value['precio']),
                'link' => ''
            ]);
            $html .= $card->render();
        }
        return $html;
    }

    public static function getFormHemocalculadora()
    {
        $formBuilder = new FormBuilder();

        $form = $formBuilder->addInput('canino', 'canino', 'number', 'Peso perro*')
            ->setPlaceholder('canino', 'Peso')
            ->setAttribute('canino', 'required', true)
            ->setAttribute('canino', 'step', '0.5')
            ->setAttribute('canino', 'min', '1')
            ->setAttribute('canino', 'max', '100')

            ->addInput('felino', 'felino', 'number', 'Peso gato*')
            ->setPlaceholder('felino', 'Peso')
            ->setAttribute('felino', 'required', true)
            ->setAttribute('felino', 'step', '0.5')
            ->setAttribute('felino', 'min', '1')
            ->setAttribute('felino', 'max', '50')
            ->build();
        return $form;
    }


    public static function getFormCrearCliente($tiposDocumento)
    {
        $formBuilder = new FormBuilder();
        $form = $formBuilder->addInput('email', 'email', 'email', 'Email*')
            ->setPlaceholder('email', 'Email')
            ->setAttribute('email', 'required', true)
            ->addInput('nombres', 'nombres', 'text', 'Nombres*')
            ->setPlaceholder('nombres', 'Nombres')
            ->setAttribute('nombres', 'required', true)
            ->addInput('apellidos', 'apellidos', 'text', 'Apellidos*')
            ->setPlaceholder('apellidos', 'Apellidos')
            ->setAttribute('apellidos', 'required', true)
            ->addSelect('tipo_documento_id', 'tipo_documento_id', 'Tipo de Documento*', $tiposDocumento)
            ->setAttribute('tipo_documento_id', 'required', true)
            ->addInput('numero_identifiacion', 'numero_identifiacion', 'number', 'NIT*')
            ->setPlaceholder('numero_identifiacion', 'nit')
            ->setAttribute('numero_identifiacion', 'required', true)
            ->addInput('razon_social', 'razon_social', 'text', 'Razon social*')
            ->setPlaceholder('razon_social', 'Razon social')
            ->setAttribute('razon_social', 'required', true)
            ->addInput('direccion_facturacion', 'direccion_facturacion', 'text', 'Dirección de facturación*')
            ->setPlaceholder('direccion_facturacion', 'Dirección de facturación')
            ->setAttribute('direccion_facturacion', 'required', true)
            ->build();
        return $form;
    }

    public static function getFormConsulta($animal_id, $cita_id, $historia_clinica_id, $veterinario_id, $tiposSangre)
    {
        $formBuilder = new FormBuilder();
        $form = $formBuilder
            ->addInput('animal_id', 'animal_id', 'hidden', '')
            ->setValue('animal_id', $animal_id)
            ->addInput('cita_id', 'cita_id', 'hidden', '')
            ->setValue('cita_id', $cita_id)

            ->addInput('historia_clinica_id', 'historia_clinica_id', 'hidden', '')
            ->setValue('historia_clinica_id', $historia_clinica_id)

            ->addInput('veterinario_id', 'veterinario_id', 'hidden', '')
            ->setValue('veterinario_id', $veterinario_id)

            ->addInput('hematocrito', 'hematocrito', 'number', 'Hematocrito*')
            ->setPlaceholder('hematocrito', 'Hematocrito')
            ->setAttribute('hematocrito', 'required', true)

            ->addInput('proteinas_totales', 'proteinas_totales', 'number', 'Proteinas totales*')
            ->setPlaceholder('proteinas_totales', 'Proteinas totales')
            ->setAttribute('proteinas_totales', 'required', true)

            ->addInput('numero_chip', 'numero_chip', 'number', 'Número de chip*')
            ->setPlaceholder('numero_chip', 'Número de chip')
            ->setAttribute('numero_chip', 'required', true)

            ->addSelect('tipo_sangre', 'tipo_sangre', 'Tipo de sangre*', $tiposSangre)
            ->setAttribute('tipo_sangre', 'required', true)

            ->addInput('peso', 'peso', 'number', 'Peso (Kilos)*')
            ->setPlaceholder('peso', 'Peso (Kilos)')
            ->setAttribute('peso', 'required', true)

            ->addTextarea('anamnesis', 'anamnesis', 'Anamnesis*')
            ->setPlaceholder('anamnesis', 'anamnesis')
            ->setAttribute('anamnesis', 'required', true)

            ->addTextarea('examen_clinico', 'examen_clinico', 'Examen Clínico*')
            ->setPlaceholder('examen_clinico', 'examen clínico')
            ->setAttribute('examen_clinico', 'required', true)

            ->addTextarea('diagnostico', 'diagnostico', 'Diagnóstico*')
            ->setPlaceholder('diagnostico', 'diagnóstico')
            ->setAttribute('diagnostico', 'required', true)

            ->addTextarea('tratamiento', 'tratamiento', 'Tratamiento*')
            ->setPlaceholder('tratamiento', 'tratamiento')
            ->setAttribute('tratamiento', 'required', true)

            ->addTextarea('observaciones', 'observaciones', 'Observaciones')
            ->setPlaceholder('observaciones', 'observaciones')

            ->addTextarea('reacciones_colaterales', 'reacciones_colaterales', 'Reacciones Colaterales')
            ->setPlaceholder('reacciones_colaterales', 'reacciones colaterales')

            ->addInput('proximo_control', 'proximo_control', 'number', 'Proximo Control (meses)*')
            ->setPlaceholder('proximo_control', 'Proximo Control')
            ->setAttribute('proximo_control', 'required', true)

            ->addCheckbox('sedacion', 'sedacion', '¿El animal necesita sedación?')

            ->addInput('medicamento_sedacion', 'medicamento_sedacion', 'text', '¿Qué medicamento necesita?')
            ->setPlaceholder('medicamento_sedacion', 'Medicamento Para Sedación')
            ->setAttribute('medicamento_sedacion', 'required', true)

            ->addCheckbox('estado_apto_donacion', 'estado_apto_donacion', 'Marcar como apto para donación.')

            ->build();

        return $form;
    }

    public static function getFormAgendarPrimeraCita($animal_id, $veterinarios)
    {
        $formBuilder = new FormBuilder();
        $form = $formBuilder->addInput('fecha', 'fecha', 'date', 'Fecha')
            ->setPlaceholder('fecha', 'Fecha')
            ->setAttribute('fecha', 'required', true)
            ->setAttribute('fecha', 'data-workday', 'true')

            ->addInput('animal_id', 'animal_id', 'hidden')
            ->setValue('animal_id', $animal_id)

            ->addSelect('veterinario_id', 'veterinario_id', 'Veterinario', $veterinarios)
            ->setPlaceholder('veterinario_id', 'Veterinario')
            ->setAttribute('veterinario_id', 'required', true)
            ->addSelect('hora', 'hora', 'Hora', [
                '-1' => '-Seleccione-',
                '9:00:00' => '9:00 AM',
                '9:30:00' => '9:30 AM',
                '10:00:00' => '10:00 AM',
                '10:30:00' => '10:30 AM',
                '11:00:00' => '11:00 AM',
                '11:30:00' => '11:30 AM',
                '12:00:00' => '12:00 PM',
                '13:00:00' => '1:00 PM',
                '13:30:00' => '1:30 PM',
                '14:00:00' => '2:00 PM',
                '14:30:00' => '2:30 PM',
                '15:00:00' => '3:00 PM',
                '15:30:00' => '3:30 PM',
                '16:00:00' => '4:00 PM',
                '16:30:00' => '4:30 PM',
                '17:00:00' => '5:00 PM'
            ])
            ->setPlaceholder('hora', 'Hora')
            ->setAttribute('hora', 'required', true)
            ->build();
        return $form;
    }

    public static function getFormRegistrarAnimal($tiposDocumento, $sexo, $especies, $tipoAlimentacion)
    {
        $formBuilder = new FormBuilder();
        $form = $formBuilder
            ->addInput('nombres', 'nombres', 'text', 'Nombres')
            ->setPlaceholder('nombres', 'Mis nombres')
            ->setAttribute('nombres', 'required', true)

            ->addInput('apellidos', 'apellidos', 'text', 'Apellidos')
            ->setPlaceholder('apellidos', 'Mis apellidos')
            ->setAttribute('apellidos', 'required', true)

            ->addSelect('tipo_documento_id', 'tipo_documento_id', 'Tipo de Documento', $tiposDocumento)
            ->setPlaceholder('tipo_documento_id', 'Seleccione el tipo de documento')
            ->setAttribute('tipo_documento_id', 'required', true)
            ->setValue('tipo_documento_id', -1)

            ->addInput('numero_documento', 'numero_documento', 'number', 'Numero de documento')
            ->setPlaceholder('numero_documento', 'Ingrese su número de documento')
            ->setAttribute('numero_documento', 'required', true)
            ->setAttribute('numero_documento', 'minlength', 8)

            ->addInput('telefono', 'telefono', 'tel', 'Celular')
            ->setPlaceholder('telefono', 'teléfono')
            ->setAttribute('telefono', 'required', true)
            // ->setAttribute('telefono', ' pattern', '[0-9]{3}-[0-9]{3}-[0-9]{4}')

            ->addInput('direccion', 'direccion', 'text', 'Dirección')
            ->setPlaceholder('direccion', 'Dirección')
            ->setAttribute('direccion', 'required', true)
            ->setAttribute('direccion', 'minlength', '10')

            ->addInput('email', 'email', 'email', 'Correo')
            ->setPlaceholder('email', 'Correo')
            ->setAttribute('email', 'required', true)
            // ->setAttribute('email', 'pattern', '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$')

            ->addInput('nombre', 'nombre', 'text', 'Nombre')
            ->setPlaceholder('nombre', 'Nombre')
            ->setAttribute('nombre', 'required', true)

            ->addInput('fecha_nacimiento', 'fecha_nacimiento', 'date', 'Fecha de nacimiento')
            ->setPlaceholder('fecha_nacimiento', 'Fecha de nacimiento')
            ->setAttribute('fecha_nacimiento', 'required', true)

            ->addSelect('sexo_id', 'sexo_id', 'Sexo', $sexo)
            ->setPlaceholder('sexo_id', 'Sexo')
            ->setAttribute('sexo_id', 'required', true)

            ->addSelect('especie_id', 'especie_id', 'Especie', $especies)
            ->setPlaceholder('especie_id', 'Especi')
            ->setAttribute('especie_id', 'required', true)

            ->addSelect('tipo_alimentacion_id', 'tipo_alimentacion_id', 'Tipo Alimentación', $tipoAlimentacion)
            ->setPlaceholder('tipo_alimentacion_id', 'Tipo Alimentación')
            ->setAttribute('tipo_alimentacion_id', 'required', true)
            ->build();
        return $form;
    }

    public static function getTableCitas()
    {
        // $model = new Cita();
        // $citas = $model->findCitas()->fetchAll(\PDO::FETCH_ASSOC);

        // $strategy = new CitaControlDataStrategy();
        // ArrayModifierHelper::addControlsToArray($strategy, $citas);
        // ArrayModifierHelper::moodifyItem($strategy, $citas);

        // $table = UIFactory::createTable($citas, new TableHeader([
        //     new TableColumn('id', '5%'),
        //     new TableColumn('Estado'),
        //     new TableColumn('Fecha'),
        //     new TableColumn('Hora'),
        //     new TableColumn('Motivo'),
        //     new TableColumn('Animal'),
        //     new TableColumn('Chip'),
        //     new TableColumn('Dueño'),
        //     new TableColumn('Email'),
        //     new TableColumn('Teléfono'),
        //     new TableColumn('Veterinario'),
        //     new TableColumn('TP'),
        //     new TableColumn('Acciones', '10%'),
        // ]), 'table');
        // return $table;
    }

    // public static function getTableClientes()
    // {
    //     $model = new Cliente();
    //     $citas = $model->findClientes()->fetchAll(\PDO::FETCH_ASSOC);

    //     $strategy = new ClientesDataStrategy();
    //     ArrayModifierHelper::modifyInfoColumn('controls', $strategy, 'addControls', $citas);

    //     $table = UIFactory::createTable($citas, new TableHeader([
    //         new TableColumn('id', '5%'),
    //         new TableColumn('Código'),
    //         new TableColumn('Razón social'),
    //         new TableColumn('Nit'),
    //         new TableColumn('Contacto'),
    //         new TableColumn('Email'),
    //         new TableColumn('Dirección facturación'),
    //         new TableColumn('Estado cliente'),
    //         new TableColumn('Acciones', '10%'),
    //     ]), 'table');
    //     return $table;
    // }

    public static function getTableNuevosAnimalesRegistrados()
    {
        // $model = new Animal();
        // $animales = $model->getAnimalesRegistrados();

        // $tableConfig = new TableConfig();
        // $tableConfig->setTableClasses('table table-sm align-middle table-hover table-bordered');
        // $tableConfig->setAttribute('id', 'table');
        // $table = new BootstrapTable($tableConfig, $animales);
        // $table->setHeader(new TableHeader([
        //     new TableColumn('id', '5%'),
        //     new TableColumn('Nombre'),
        //     new TableColumn('Edad'),
        //     new TableColumn('Especie'),
        //     new TableColumn('Sexo'),
        //     new TableColumn('Dueño'),
        //     new TableColumn('Email'),
        //     new TableColumn('Celular'),
        //     new TableColumn('Fecha registro'),
        //     new TableColumn('Acciones', '10%'),
        // ]));
        // return $table;
    }

    public static function getNuevosRegistrosView()
    {
        // $model = new Animal();
        // $animales = $model->getAnimalesRegistrados();

        // $tableConfig = new TableConfig();
        // $tableConfig->setTableClasses('table table-sm align-middle table-hover table-bordered');
        // $tableConfig->setAttribute('id', 'table');
        // $table = new BootstrapTable($tableConfig, $animales);
        // $table->setHeader(new TableHeader([
        //     new TableColumn('id', '5%'),
        //     new TableColumn('Nombre'),
        //     new TableColumn('Edad'),
        //     new TableColumn('Especie'),
        //     new TableColumn('Sexo'),
        //     new TableColumn('Dueño'),
        //     new TableColumn('Email'),
        //     new TableColumn('Celular'),
        //     new TableColumn('Fecha registro'),
        //     new TableColumn('Acciones', '10%'),
        // ]));
        // $view = new View('admin.nuevosregistros', ['table' => $table]);
        // return $view;
    }

    public static function createMainView(string $title, View $sidebar, View $contentHeader, View $content = null)
    {
        $navBar = new View('dashboard.navbar');
        $uiDashboardView = new View('dashboard');
        $uiDashboardView->with('title', $title);
        $uiDashboardView->with('navbar', $navBar);
        $uiDashboardView->with('sidebar', $sidebar);
        $uiDashboardView->with('content-header', $contentHeader);
        $uiDashboardView->with('content', $content == null ? 'El contenido de la pagina no existe' : $content);
        return $uiDashboardView;
    }
}
