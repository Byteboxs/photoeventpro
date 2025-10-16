<?php

namespace app\services\admin\vendedores\ui;

use app\helpers\ModelToUIHelper;
use app\helpers\RouteHelper;
use app\models\Document_type;
use app\services\ui\form\Form;
use app\services\ui\form\FormBuilder;
use app\services\ui\html\HtmlFactory;
use app\services\ui\paginator\Paginator;

class UIVendedoresService
{
    private $formBuilder;
    private $documentTypes;
    private $args;
    public function __construct()
    {
        $this->formBuilder = new FormBuilder();
        $model = new Document_type();
        $result = $model->findWhere(['status' => 'activo']);
        $this->documentTypes = ModelToUIHelper::make()->formatDataForSelect($result, 'id', 'nombre');
    }

    private function getSubmitBtn(string $name, string $title, string $icon)
    {
        return HtmlFactory::create('button', [
            'class' => 'btn btn-outline-primary form-control my-2',
            'type' => 'submit',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'left',
            'data-bs-custom-class' => 'custom-tooltip',
            'data-bs-title' => $title,
        ])->addChild(
            HtmlFactory::create('icon', [
                'class' => $icon . ' mx-2',
            ])
        )->addChild($name);
    }

    public function getBtnEditar()
    {
        return $this->getSubmitBtn(
            'Editar',
            'Haga click aca para editar los datos del vendedor',
            'fas fa-user-edit mx-2'
        );
    }

    public function getBtnRegistrar()
    {
        return $this->getSubmitBtn(
            'Registrar',
            'Haga click aca para registrar un nuevo vendedor al sistema',
            'fas fa-user-plus mx-2'
        );
    }

    public function getLinkCancelar($url, $ui = 'save')
    {
        if ($ui == 'save')
            return $this->getCancelLink($url, 'Haga click aca para cancelar el registro del vendedor');
        if ($ui == 'update')
            return $this->getCancelLink($url, 'Haga click aca cancelar la actualizacion del vendedor');
    }

    public function getCancelLink($url, $title)
    {
        return HtmlFactory::create('link', [
            'class' => 'btn btn-outline-danger form-control my-2',
            'href' => $url,
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'left',
            'data-bs-custom-class' => 'custom-tooltip',
            'data-bs-title' => 'Haga click aca para cancelar el registro del vendedor',
        ])->addChild(
            HtmlFactory::create('icon', [
                'class' => 'fas fa-times mx-2',
            ])
        )->addChild('Cancelar');
    }

    public function getLinkCreateVendedor($url)
    {
        return HtmlFactory::create('link', [
            'class' => 'btn btn-label-info',
            'href' => $url,
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'left',
            'title' => 'Crear vendedor',
        ])->addChild(
            HtmlFactory::create('icon', [
                'class' => 'fas fa-user-plus mx-2',
            ])
        )->addChild('Crear');
    }

    public function getEevntoVendedoresCards(array $vendedores, string $type = 'disponibles')
    {
        $vendedoresCards = '';
        if (count($vendedores) > 0) {
            if ($type == 'disponibles') {
                foreach ($vendedores as $vendedor) {
                    $vendedoresCards .= $this->getVendedorCardDragable($vendedor);
                }
            }
            if ($type == 'seleccionados') {
                foreach ($vendedores as $vendedor) {
                    $vendedoresCards .= $this->getVendedorCardPreloaded($vendedor);
                }
            }
        }
        return $vendedoresCards;
    }


    public function getVendedorCardDragable($vendedor)
    {
        $primerCaracter = strtoupper(str_split($vendedor->primer_nombre)[0]);
        $segundoCaracter = strtoupper(str_split($vendedor->primer_apellido)[0]);
        $avatarName = $primerCaracter . $segundoCaracter;
        $vendorInfo = HtmlFactory::create('div', [
            'class' => 'vendor-info',
        ]);
        $vendorInfo->addChild(
            HtmlFactory::create('div', [
                'class' => 'vendor-avatar',
            ], $avatarName)
        );
        $vendorInfo->addChild(
            HtmlFactory::create('div')
                ->addChild(
                    HtmlFactory::create('div', [
                        'class' => 'vendor-name',
                    ], $vendedor->primer_nombre . ' ' . $vendedor->primer_apellido)
                )
                ->addChild(
                    HtmlFactory::create(
                        'div',
                        [
                            'class' => 'vendor-meta',
                        ]
                    )
                        ->addChild('Email: ')
                        ->addChild(
                            HtmlFactory::create(
                                'span',
                                [
                                    'class' => 'text-success'
                                ],
                                $vendedor->email
                            )
                        )
                )

        );

        $card = HtmlFactory::create('div', [
            'class' => 'vendor-card draggable',
            'draggable' => 'true',
            'data-id' => $vendedor->user_id,
        ]);
        $card->addChild($vendorInfo);
        $card->addChild(
            HtmlFactory::create('div')
                ->addChild(
                    HtmlFactory::create('icon', [
                        'class' => 'fas fa-grip-lines drag-icon',
                    ])
                )
        );
        return $card;
    }
    public static function getVendedorCardPreloaded($vendedor)
    {
        $primerCaracter = strtoupper(str_split($vendedor->primer_nombre)[0]);
        $segundoCaracter = strtoupper(str_split($vendedor->primer_apellido)[0]);
        $avatarName = $primerCaracter . $segundoCaracter;
        $vendorInfo = HtmlFactory::create('div', [
            'class' => 'vendor-info',
        ]);
        $vendorInfo->addChild(
            HtmlFactory::create('div', [
                'class' => 'vendor-avatar',
            ], $avatarName)
        );
        $vendorName = HtmlFactory::create('div', [
            'class' => 'vendor-name',
        ])->addChild($vendedor->primer_nombre . ' ' . $vendedor->primer_apellido);
        $badgePrecargado = HtmlFactory::create('span', ['class' => 'preloaded-badge'], 'Precargado');
        $vendorName->addChild(
            $badgePrecargado
        );
        if ($vendedor->vendor_status == 'bloqueado') {
            $badgeBloqueado = HtmlFactory::create('span', ['class' => 'locked-badge'], 'Bloqueado');
            $vendorName->addChild(
                $badgeBloqueado
            );
        }
        $vendorInfo->addChild(
            HtmlFactory::create('div')
                ->addChild(
                    $vendorName
                )
                ->addChild(
                    HtmlFactory::create(
                        'div',
                        [
                            'class' => 'vendor-meta',
                        ]
                    )
                        ->addChild('Email: ')
                        ->addChild(
                            HtmlFactory::create(
                                'span',
                                [
                                    'class' => 'text-success'
                                ],
                                $vendedor->email
                            )
                        )
                )
        );
        $card = HtmlFactory::create('div', [
            'class' => 'vendor-card preloaded',
            'data-preloaded' => 'true',
            'data-id' => $vendedor->user_id,
        ]);
        $card->addChild($vendorInfo);

        $button = HtmlFactory::create('button', [
            'class' => 'action-btn remove-btn preloaded-remove-btn',
            'data-id' => $vendedor->user_id,
        ])->addChild(
            HtmlFactory::create('icon', [
                'class' => 'fas fa-times',
            ])
        );

        $locked = HtmlFactory::create('div')->addChild(
            HtmlFactory::create('icon', [
                'class' => 'fas fa-lock text-muted',
            ])
        );

        if ($vendedor->vendor_status == 'bloqueado') {
            $card->addChild($locked);
        } else {
            $card->addChild($button);
        }

        return $card;
    }

    public function getUIVendedoresDisponibles() {}
    public function getPaginatorUIVendedoresDisponibles($result, $page, $event_id)
    {
        return new Paginator(
            $page,
            RouteHelper::getUrlFor('eventoVendedoresView', ['evento_id' => $event_id]),
            $result['currentPage'],
            $result['totalPages'],
            $result['perPage'],
            $result['totalData'],
        );
    }

    public function getTableVendedores($data)
    {
        $strategy = new VendedoresStrategy();
        $columnNames = [
            ['id', '5%'],
            // ['', '5%'],
            // 'primer_nombre',
            // 'segundo_nombre',
            // 'primer_apellido',
            // 'segundo_apellido',
            'Vendedor', // nombre_completo
            // 'role_name',
            // 'email',
            'dirección', // direccion
            'teléfono', // telefono
            'tipo documento', // tipo_documento
            // 'codigo_tipo_documento',
            'número', // numero_identificacion
            'estado', // status
            '' // status
        ];
        return (new VendedoresTableService($strategy, $data, $columnNames))->get();
    }

    public function getPaginatorVendedores($result, $page)
    {
        return new Paginator(
            $page,
            RouteHelper::getUrlFor('vendedoresView'),
            $result['currentPage'],
            $result['totalPages'],
            $result['perPage'],
            $result['totalData'],
        );
    }

    public function getForm(string $type = 'registrar', $args = null): Form
    {
        if ($type === 'registrar') {
            $this->initRegistrarForm();
        } elseif ($type === 'editar') {
            $this->initEditarForm($args);
        }
        return $this->formBuilder->build();
    }


    private function initRegistrarForm(): Form
    {
        return $this->formBuilder
            ->addInput('primer_nombre', 'primer_nombre', 'text', 'Primer Nombre*')
            ->setPlaceholder('primer_nombre', 'Ingrese el primer nombre')
            ->setAttribute('primer_nombre', 'required', true)

            ->addInput('segundo_nombre', 'segundo_nombre', 'text', 'Segundo Nombre')
            ->setPlaceholder('segundo_nombre', 'Ingrese el segundo nombre')

            ->addInput('primer_apellido', 'primer_apellido', 'text', 'Primer Apellido*')
            ->setPlaceholder('primer_apellido', 'Ingrese el primer apellido')
            ->setAttribute('primer_apellido', 'required', true)

            ->addInput('segundo_apellido', 'segundo_apellido', 'text', 'Segundo Apellido')
            ->setPlaceholder('segundo_apellido', 'Ingrese el segundo apellido')

            ->addInput('email', 'email', 'email', 'Email*')
            ->setPlaceholder('email', 'Ingrese el email')
            ->setAttribute('email', 'required', true)

            ->addInput('direccion', 'direccion', 'text', 'Dirección de residencia')
            ->setPlaceholder('direccion', 'Ingrese la dirección')

            ->addInput('telefono', 'telefono', 'tel', 'Teléfono')
            ->setPlaceholder('telefono', 'Ingrese el teléfono')
            ->setAttribute('telefono', 'required', true)

            ->addSelect('document_type_id', 'document_type_id', 'Tipo de Documento*', $this->documentTypes)
            ->setPlaceholder('document_type_id', 'Seleccione el tipo de documento')
            ->setAttribute('document_type_id', 'required', true)

            ->addInput('numero_identificacion', 'numero_identificacion', 'number', 'Numero de documento*')
            ->setPlaceholder('numero_identificacion', 'Ingrese su número de documento')
            ->setAttribute('numero_identificacion', 'required', true)
            ->setAttribute('numero_identificacion', 'minlength', 7)

            ->addInput('password', 'password', 'password', 'Contraseña*')
            ->setPlaceholder('password', 'Ingrese la contraseña')
            ->setAttribute('password', 'required', true)
            ->addInput('confirm_password', 'confirm_password', 'password', 'Confirmar Contraseña*')
            ->setPlaceholder('confirm_password', 'Confirme la contraseña')
            ->setAttribute('confirm_password', 'required', true)

            ->build();
    }
    private function initEditarForm($args): Form
    {
        $checked = $args->status == 'activo' ? true : false;
        $this->formBuilder
            ->addInput('user_id', 'user_id', 'hidden', '')
            ->setValue('user_id', $args->user_id)

            ->addInput('primer_nombre', 'primer_nombre', 'text', 'Primer Nombre*')
            ->setPlaceholder('primer_nombre', 'Ingrese el primer nombre')
            ->setAttribute('primer_nombre', 'required', true)
            ->setValue('primer_nombre', $args->primer_nombre)

            ->addInput('segundo_nombre', 'segundo_nombre', 'text', 'Segundo Nombre')
            ->setPlaceholder('segundo_nombre', 'Ingrese el segundo nombre')
            ->setValue('segundo_nombre', $args->segundo_nombre)

            ->addInput('primer_apellido', 'primer_apellido', 'text', 'Primer Apellido*')
            ->setPlaceholder('primer_apellido', 'Ingrese el primer apellido')
            ->setAttribute('primer_apellido', 'required', true)
            ->setValue('primer_apellido', $args->primer_apellido)

            ->addInput('segundo_apellido', 'segundo_apellido', 'text', 'Segundo Apellido')
            ->setPlaceholder('segundo_apellido', 'Ingrese el segundo apellido')
            ->setValue('segundo_apellido', $args->segundo_apellido)

            ->addInput('email', 'email', 'email', 'Email*')
            ->setPlaceholder('email', 'Ingrese el email')
            ->setAttribute('email', 'required', true)
            ->setValue('email', $args->email)

            ->addInput('direccion', 'direccion', 'text', 'Dirección de residencia')
            ->setPlaceholder('direccion', 'Ingrese la dirección')
            ->setValue('direccion', $args->direccion)

            ->addInput('telefono', 'telefono', 'tel', 'Teléfono')
            ->setPlaceholder('telefono', 'Ingrese el teléfono')
            ->setAttribute('telefono', 'required', true)
            ->setValue('telefono', $args->telefono)

            ->addSelect('document_type_id', 'document_type_id', 'Tipo de Documento*', $this->documentTypes)
            ->setPlaceholder('document_type_id', 'Seleccione el tipo de documento')
            ->setAttribute('document_type_id', 'required', true)
            ->setValue('document_type_id', $args->tipo_documento_id)

            ->addInput('numero_identificacion', 'numero_identificacion', 'number', 'Numero de documento*')
            ->setPlaceholder('numero_identificacion', 'Ingrese su número de documento')
            ->setAttribute('numero_identificacion', 'required', true)
            ->setAttribute('numero_identificacion', 'minlength', 7)
            ->setValue('numero_identificacion', $args->numero_identificacion)

            ->addSwitchCheckbox('status', 'status', 'Estado del vendedor')
            ->setValue('status', $args->status);
        $checked = $args->status == 'activo' ? true : false;
        if ($checked) {
            $this->formBuilder->setAttribute('status', 'checked', $checked);
        }
        return $this->formBuilder->build();
    }
}
