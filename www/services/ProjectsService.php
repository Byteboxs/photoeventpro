<?php

namespace app\services;

use app\core\Application;
use app\core\Singleton;
use app\helpers\RouteHelper;
use app\models\Billing_information;
use app\models\Customer;
use app\models\Customers_event;
use app\models\Institution;
use app\models\Location;
use app\models\Order_detail;
use app\models\Payment;
use app\models\Picture;
use app\models\Project;
use app\models\Purchase_order;
use app\models\Selected_picture;
use app\models\Service;
use app\models\Shipping_information;
use app\models\User;
use ZipArchive;

class ProjectsService extends Singleton
{
    private $builder;
    private $logger;
    protected function __construct()
    {
        parent::__construct();
        $this->builder = Application::$app->builder;
        $this->logger = Application::$app->logger;
    }

    public function createProjectInstitutionLocation(array $data)
    {
        $result = $this->builder->transaction(function ($db) use ($data) {
            $institution = new Institution();
            $institution->nombre = $data['institution']['nombre'];
            $institution = $institution->findOrCreate('nombre');

            $location = new Location();
            $location->nombre = $data['location']['nombre'];
            $location->direccion = $data['location']['direccion'];
            $location = $location->findOrCreate('nombre');

            $project = new Project();
            $project->institution_id = $institution->id;
            $project->pricing_plans_id = $data['project']['pricing_plans_id'];

            $project->location_id = $location->id;
            $project->nombre_evento = $data['project']['nombre_evento'];
            $project->fecha_inicio = $data['project']['fecha_inicio'];
            $project->hora_ceremonia = $data['project']['hora_ceremonia'];
            $project->fecha_fin = $data['project']['fecha_fin'];
            $project->descripcion = $data['project']['descripcion'];
            $project->status = 'activo';
            $project->save();
            return $project;
        });
        return $result;
    }

    public function createProjectLocation(array $data)
    {
        $result = $this->builder->transaction(function ($db) use ($data) {
            $location = new Location();
            $location->nombre = $data['location']['nombre'];
            $location->direccion = $data['location']['direccion'];
            $location = $location->findOrCreate('nombre');

            $project = new Project();
            $project->institution_id = $data['institution']['institution_id'];
            $project->location_id = $location->id;
            $project->nombre_evento = $data['project']['nombre_evento'];
            $project->fecha_inicio = $data['project']['fecha_inicio'];
            $project->hora_ceremonia = $data['project']['hora_ceremonia'];
            $project->fecha_fin = $data['project']['fecha_fin'];
            $project->descripcion = $data['project']['descripcion'];
            $project->status = 'activo';
            $project->save();
            return $project;
        });
        return $result;
    }

    public function createProjectInstitution(array $data)
    {
        $result = $this->builder->transaction(function ($db) use ($data) {
            $institution = new Institution();
            $institution->nombre = $data['institution']['nombre'];
            $institution = $institution->findOrCreate('nombre');

            $project = new Project();
            $project->institution_id = $institution->id;
            $project->location_id = $data['location']['location_id'];
            $project->nombre_evento = $data['project']['nombre_evento'];
            $project->fecha_inicio = $data['project']['fecha_inicio'];
            $project->hora_ceremonia = $data['project']['hora_ceremonia'];
            $project->fecha_fin = $data['project']['fecha_fin'];
            $project->descripcion = $data['project']['descripcion'];
            $project->status = 'activo';
            $project->save();
            return $project;
        });
        return $result;
    }

    public function createProject(array $data)
    {
        $result = $this->builder->transaction(function ($db) use ($data) {
            $project = new Project();
            $project->institution_id = $data['institution']['institution_id'];
            $project->location_id = $data['location']['location_id'];
            $project->nombre_evento = $data['project']['nombre_evento'];
            $project->fecha_inicio = $data['project']['fecha_inicio'];
            $project->hora_ceremonia = $data['project']['hora_ceremonia'];
            $project->fecha_fin = $data['project']['fecha_fin'];
            $project->descripcion = $data['project']['descripcion'];
            $project->status = 'activo';
            $project->save();
            return $project;
        });
        return $result;
    }
    /**
     * Verifica si el proyecto existe
     */
    private function verifyProject(int $projectId): array
    {
        $project = new Project();
        $project = $project->find($projectId);

        if (!$project) {
            return ['success' => false, 'message' => 'Proyecto no encontrado.'];
        }

        return ['success' => true, 'data' => $project];
    }

    /**
     * Verifica si el cliente existe
     */
    private function verifyCustomer(int $customerId): array
    {
        $customer = new Customer();
        $customer = $customer->find($customerId);

        if (!$customer) {
            return ['success' => false, 'message' => 'Cliente no encontrado.'];
        }

        return ['success' => true, 'data' => $customer];
    }

    /**
     * Verifica si existe la relación cliente-proyecto
     */
    private function verifyCustomerEvent(int $customerId, int $projectId): array
    {
        $customerEvent = new Customers_event();
        $customerEvent = $customerEvent->findWhere([
            'customer_id' => $customerId,
            'project_id' => $projectId
        ]);

        if (!$customerEvent) {
            return ['success' => false, 'message' => 'No se encuentra el cliente en el proyecto.'];
        }

        return ['success' => true, 'data' => $customerEvent];
    }
    /**
     * Elimina únicamente los archivos pertenecientes a la carga actual que falló
     * para mantener la coherencia entre la BD y el sistema de archivos
     */
    private function cleanupCurrentUploadedFiles(array $files, string $uploadPathUrl): void
    {
        // Si no hay archivos o no hay ruta, salir
        if (empty($files) || empty($uploadPathUrl)) {
            return;
        }

        $dirPath = rtrim($uploadPathUrl, '/');

        // Eliminar solo los archivos de la carga actual que falló
        foreach ($files as $fileName) {
            $filePath = $dirPath . '/' . $fileName;
            if (file_exists($filePath)) {
                @unlink($filePath);
                $this->logger->log('info', "Archivo eliminado debido a fallo en transacción: {$filePath}");
            }
        }
    }
    public function loadClientImages(array $data): array
    {
        $result = $this->builder->transaction(function ($db) use ($data) {

            $currentUploadedFiles = [];
            $errorMessage = '';

            $projectId = $data['proyecto_id'] ?? null;
            $customerId = $data['cliente_id'] ?? null;
            $fileUploadResult = $data['file_upload_result'] ?? null;
            $uploadPathUrl = $data['upload_path_url'] ?? null;

            if (!$projectId || !$customerId || !$fileUploadResult || !$uploadPathUrl) {
                return ['status' => 'fail', 'message' => 'Datos incompletos para cargar imágenes.'];
            }

            // 1. Verificar project y customer
            $project = $this->verifyProject($projectId);
            if (!$project['success']) {
                return ['status' => 'fail', 'message' => $project['message']];
            }

            $customer = $this->verifyCustomer($customerId);
            if (!$customer['success']) {
                return ['status' => 'fail', 'message' => $customer['message']];
            }

            // 2. Verificar si existe el cliente en el proyecto
            $customerEvent = $this->verifyCustomerEvent($customerId, $projectId);
            if (!$customerEvent['success']) {
                return ['status' => 'fail', 'message' => $customerEvent['message']];
            }

            // --- 3. Iterar sobre los archivos cargados e insertar en la BD (con verificación de duplicados) ---
            $uploadDetails = $fileUploadResult['details'];
            if (!is_array($uploadDetails)) {
                return ['status' => 'fail', 'message' => 'Formato incorrecto en detalles de carga de archivos.'];
            }

            $duplicates_found = false; // Flag para indicar si se encontraron duplicados

            foreach ($uploadDetails as $fileInfo) {
                if (isset($fileInfo['file']) && isset($fileInfo['status']) && $fileInfo['status'] === 'success') {
                    $currentUploadedFiles[] = $fileInfo['file'];
                    $fileName = $fileInfo['file'];
                    $filePath = rtrim($uploadPathUrl, '/') . '/' . $fileName;

                    // Verificar si el archivo ya existe para este customer_event
                    $existingPicture = new Picture();
                    $existingPicture = $existingPicture->findWhere([
                        'customers_events_id' => $customerEvent['data']->id,
                        'file_path' => $filePath
                    ]);

                    if ($existingPicture) {
                        $duplicates_found = true;
                        $this->logger->log('warning', "Archivo duplicado detectado y omitido: '{$fileName}' para el evento del cliente ID {$customerEvent['data']->id}.");
                        $errorMessage .= "Archivo: '{$fileName}' duplicado<br>. ";
                        continue; // Saltar a la siguiente iteración si es duplicado
                    }

                    $picture = new Picture();
                    $picture->customers_events_id = $customerEvent['data']->id;
                    $picture->file_path = $filePath;
                    $picture->fecha_captura = date('Y-m-d H:i:s');
                    if (!$picture->save()) {
                        // Eliminar todos los archivos de la carpeta $uploadPathUrl si falla el guardado en la BD ya que se realiza un rollback
                        $this->cleanupCurrentUploadedFiles($currentUploadedFiles, $uploadPathUrl);
                        $this->logger->log('error', "Error al guardar la imagen '{$fileName}' en la base de datos.");
                        return [
                            'status' => 'fail',
                            'message' => "Error al guardar la imagen '{$fileName}' en la base de datos."
                        ];
                    }
                } elseif (isset($fileInfo['file']) && isset($fileInfo['status']) && $fileInfo['status'] !== 'success') {
                    $fileName = $fileInfo['file'];
                    $error_message = isset($fileInfo['message']) ? $fileInfo['message'] : 'Error desconocido al cargar el archivo.';
                    $this->logger->log('warning', "Error al cargar el archivo '{$fileName}': {$error_message}");
                    $errorMessage .= "Error '{$fileName}'. {$error_message} ";
                    continue;
                }
            }

            $status = "success";

            $finalMessage = 'El proceso termino exitosamente.';
            if ($errorMessage !== '') {
                $finalMessage .= "\n" . ' Errores detectados:' . "\n" . $errorMessage;
                $status = "warning";
            }

            return [
                'status' => $status,
                'message' => $finalMessage,
                'data' => $data
            ];
        });
        return $result;
    }

    public function makeCashPayment(array $data): array
    {
        $result = $this->builder->transaction(function ($db) use ($data) {
            $projectId = $data['project_id'];
            $customerId = $data['client_id'];
            $salesperson_id = $data['salesperson_id'];

            $items = $data['items'];

            // Verificar project y customer (Active record)
            $project = new Project();
            $project = $project->find($projectId);

            if (!$project) {
                return ['status' => 'fail', 'message' => 'Proyecto no encontrado.'];
            }

            $customer = new Customer();
            $customer = $customer->find($customerId);

            if (!$customer) {
                return ['status' => 'fail', 'message' => 'Cliente no encontrado.'];
            }

            // --- 2. Verificar si ya existe Customers_events (Active Record) ---
            $customerEvent = new Customers_event();
            $customerEvent = $customerEvent->findWhere([
                'customer_id' => $customerId,
                'project_id' => $projectId
            ]);

            if (!$customerEvent) {
                return ['status' => 'fail', 'message' => 'No se encuentra el cliente en el proyecto.'];
            }

            // Buscar informacion de facturacion y envio para el cliente
            $billingINformation = new Billing_information();
            $billingINformation = $billingINformation->findWhere([
                'customer_id' => $customerId
            ]);

            if (!$billingINformation) {
                return ['status' => 'fail', 'message' => 'No se encuentra la información de facturación del cliente.'];
            }

            $shippingInformation = new Shipping_information();
            $shippingInformation = $shippingInformation->findWhere([
                'customer_id' => $customerId
            ]);

            if (!$shippingInformation) {
                return ['status' => 'fail', 'message' => 'No se encuentra la informacion de envio del cliente.'];
            }

            $purchaseOrder = new Purchase_order();
            $purchaseOrder->customer_id = $customerId;
            $purchaseOrder->salesperson_id = $salesperson_id;
            $purchaseOrder->billing_information_id = $billingINformation->id;
            $purchaseOrder->project_id = $projectId;
            $purchaseOrder->shipping_information_id = $shippingInformation->id;
            $purchaseOrder->fecha_orden = date('Y-m-d H:i:s');
            $purchaseOrder->total_bruto = 0;
            $purchaseOrder->total_neto = 0;
            $purchaseOrder->estado_pago = 'pagado';
            $purchaseOrder->metodo_pago = 'efectivo';
            $purchaseOrder = $purchaseOrder->save();

            if (!$purchaseOrder) {
                return [
                    'status' => 'fail',
                    "message" => 'Error al crear la Orden de Compra.'
                ];
            }

            $purchaseOrderId = $purchaseOrder->id;
            $totalBruto = 0;
            $totalNeto = 0;

            // --- 4. Iterar sobre los servicios y crear Order Details (Active Record) ---
            foreach ($items as $item) {
                $serviceName = $item['name'];
                $cantidad = $item['quantity'];
                $precioUnitario = $item['price'];

                $service = new Service();
                $service = $service->findWhere([
                    'nombre' => $serviceName
                ]);
                if (!$service) {
                    return [
                        'status' => 'fail',
                        "message" => "Servicio '{$serviceName}' no encontrado."
                    ];
                }

                for ($i = 0; $i < $cantidad; $i++) {
                    $orderDetail = new Order_detail();
                    $orderDetail->purchase_order_id = $purchaseOrderId;
                    $orderDetail->service_id = $service->id;
                    $orderDetail->cantidad = 1;
                    $orderDetail->precio_unitario = $precioUnitario;
                    $orderDetail->subtotal = $precioUnitario;
                    $orderDetail = $orderDetail->save();

                    if (!$orderDetail) {
                        return [
                            'status' => 'fail',
                            "message" => "Error al crear el detalle de la orden para el servicio '{$serviceName}'."
                        ];
                    }
                }

                $subtotal = $precioUnitario * $cantidad;
                $totalBruto += $subtotal;
                $totalNeto += $subtotal;
            }

            // --- 5. Actualizar Purchase Order con totales (Active Record) ---
            $purchaseOrder->total_bruto = $totalBruto;
            $purchaseOrder->total_neto = $totalNeto;

            if (!$purchaseOrder->save()) {
                return [
                    'status' => 'fail',
                    "message" => "Error al actualizar los totales de la Orden de Compra."
                ];
            }

            return [
                'status' => 'success',
                'url' => APP_DIRECTORY_PATH . '/evento/detalle/' . $projectId,
                "message" => 'Pedido de servicios creado exitosamente.'
            ];
        });
        return $result;
    }

    private function createServices(array $items, int $purchaseOrderId)
    {
        foreach ($items as $item) {
            $service_id = $item['id'];
            $cantidad = $item['quantity'];
            $precioUnitario = $item['price'];

            $service = new Service();
            $service = $service->findWhere([
                'id' => $service_id
            ]);
            if (!$service) {
                return [
                    'status' => 'fail',
                    "message" => "Servicio '{$service->nombre}' no encontrado."
                ];
            }

            for ($i = 0; $i < $cantidad; $i++) {
                $orderDetail = new Order_detail();
                $orderDetail->purchase_order_id = $purchaseOrderId;
                $orderDetail->service_id = $service->id;
                $orderDetail->cantidad = 1;
                $orderDetail->precio_unitario = $precioUnitario;
                $orderDetail->subtotal = $precioUnitario;
                $orderDetail = $orderDetail->save();

                if (!$orderDetail) {
                    return [
                        'status' => 'fail',
                        "message" => "Error al crear el detalle de la orden para el servicio '{$service->nombre}'."
                    ];
                }
            }
        }
        return ['status' => 'success'];
    }

    private function traducePaymentMethod($method)
    {
        switch ($method) {
            case 'cash':
                return 'efectivo';
            case 'card':
                return 'tarjeta';
            case 'transfer':
                return 'transferencia';
            case 'qr':
                return 'transferencia';
            default:
                return 'desconocido';
        }
    }


    private function createPaymentMethods(array $methods, int $purchaseOrderId)
    {
        foreach ($methods as $method) {
            $paymentMethod = $this->traducePaymentMethod($method['method']);
            $payment = new Payment();
            $payment->purchase_orders_id = $purchaseOrderId;
            $payment->amount = $method['amount'];
            $payment->payment_method = $paymentMethod;
            $payment->status = 'completado';
            $payment = $payment->save();

            if (!$payment) {
                return [
                    'status' => 'fail',
                    'message' => "Error al crear el pago con método '{$method['method']}' y monto '{$method['amount']}'."
                ];
            }
        }
        return ['status' => 'success'];
    }
    public function salesProcess(array $data): array
    {
        $result = $this->builder->transaction(function ($db) use ($data) {
            $projectId = $data['event_id'];
            $user_id = $data['user_id'];
            $salesperson_id = $data['vendedor_id'];
            $totalSale = $data['total'] ?? 0;
            $amountReceived = $data['amount_received'] ?? 0;
            $changeGiven = $data['change_given'] ?? 0;

            $items = $data['items'];
            $methodsOfPayment = $data['payments'];


            $project = new Project();
            $project = $project->find($projectId);

            if (!$project) {
                return ['status' => 'fail', 'message' => 'Proyecto no encontrado.'];
            }

            $usuario = new User();
            $usuario = $usuario->find($user_id);

            if (!$usuario) {
                return ['status' => 'fail', 'message' => 'Usuario no encontrado.'];
            }

            $customer = $usuario->getCustomer();

            if (!$customer) {
                return ['status' => 'fail', 'message' => 'Cliente no encontrado.'];
            }
            $customer_id = $customer->customer_id;

            $customerEvent = new Customers_event();
            $customerEvent = $customerEvent->findWhere([
                'customer_id' => $customer_id,
                'project_id' => $projectId
            ]);

            if (!$customerEvent) {
                return ['status' => 'fail', 'message' => 'No se encuentra el cliente en el proyecto.'];
            }

            $billingINformation = new Billing_information();
            $billingINformation = $billingINformation->findWhere([
                'customer_id' => $customer_id
            ]);

            if (!$billingINformation) {
                return ['status' => 'fail', 'message' => 'No se encuentra la información de facturación del cliente.'];
            }

            $shippingInformation = new Shipping_information();
            $shippingInformation = $shippingInformation->findWhere([
                'customer_id' => $customer_id
            ]);

            if (!$shippingInformation) {
                return ['status' => 'fail', 'message' => 'No se encuentra la informacion de envio del cliente.'];
            }

            $purchaseOrder = new Purchase_order();
            $purchaseOrder->customer_id = $customer_id;
            $purchaseOrder->salesperson_id = $salesperson_id;
            $purchaseOrder->billing_information_id = $billingINformation->id;
            $purchaseOrder->project_id = $projectId;
            $purchaseOrder->shipping_information_id = $shippingInformation->id;
            $purchaseOrder->fecha_orden = date('Y-m-d H:i:s');
            $purchaseOrder->total_bruto = 0;
            $purchaseOrder->total_neto = 0;
            $purchaseOrder->estado_pago = 'pagado';
            // $purchaseOrder->metodo_pago = 'efectivo';
            $purchaseOrder = $purchaseOrder->save();

            if (!$purchaseOrder) {
                return [
                    'status' => 'fail',
                    "message" => 'Error al crear la Orden de Compra.'
                ];
            }

            $purchaseOrderId = $purchaseOrder->id;
            // $totalBruto = 0;
            // $totalNeto = 0;

            $createServicesResult = $this->createServices($items, $purchaseOrderId);
            if ($createServicesResult['status'] === 'fail') {
                return $createServicesResult;
            }

            $createPaymentMethodsResult = $this->createPaymentMethods($methodsOfPayment, $purchaseOrderId);
            if ($createPaymentMethodsResult['status'] === 'fail') {
                return $createPaymentMethodsResult;
            }


            // --- 4. Iterar sobre los servicios y crear Order Details (Active Record) ---
            // foreach ($items as $item) {
            //     $service_id = $item['id'];
            //     $cantidad = $item['quantity'];
            //     $precioUnitario = $item['price'];

            //     $service = new Service();
            //     $service = $service->findWhere([
            //         'id' => $service_id
            //     ]);
            //     if (!$service) {
            //         return [
            //             'status' => 'fail',
            //             "message" => "Servicio '{$service->nombre}' no encontrado."
            //         ];
            //     }

            //     for ($i = 0; $i < $cantidad; $i++) {
            //         $orderDetail = new Order_detail();
            //         $orderDetail->purchase_order_id = $purchaseOrderId;
            //         $orderDetail->service_id = $service->id;
            //         $orderDetail->cantidad = 1;
            //         $orderDetail->precio_unitario = $precioUnitario;
            //         $orderDetail->subtotal = $precioUnitario;
            //         $orderDetail = $orderDetail->save();

            //         if (!$orderDetail) {
            //             return [
            //                 'status' => 'fail',
            //                 "message" => "Error al crear el detalle de la orden para el servicio '{$service->nombre}'."
            //             ];
            //         }
            //     }

            //     // $subtotal = $precioUnitario * $cantidad;
            //     // $totalBruto += $subtotal;
            //     // $totalNeto += $subtotal;
            // }

            // $purchaseOrder->total_bruto = $totalBruto;
            // $purchaseOrder->total_neto = $totalNeto;
            $purchaseOrder->total_bruto = $totalSale;
            $purchaseOrder->total_neto = $totalSale;

            if (!$purchaseOrder->save()) {
                return [
                    'status' => 'fail',
                    "message" => "Error al actualizar los totales de la Orden de Compra."
                ];
            }

            return [
                'status' => 'success',
                'url' => APP_DIRECTORY_PATH . '/evento/detalle/' . $projectId,
                "message" => 'Pedido de servicios creado exitosamente.'
            ];
        });
        return $result;
    }

    public function saveSelectedImages($order_detail_id, $images)
    {
        return $this->builder->transaction(function ($db) use ($order_detail_id, $images) {
            foreach ($images as $image) {
                $selectedPicture = new Selected_picture();
                $selectedPicture->picture_id = $image->picture_id;
                $selectedPicture->order_index = $image->order_index;
                $selectedPicture->order_detail_id = $order_detail_id;
                $selectedPicture = $selectedPicture->save();
                if (!$selectedPicture) {
                    return [
                        'status' => 'fail',
                        "message" => "Error al seleccionar la imagen del servicio."
                    ];
                }
            }
            return [
                'status' => 'success',
                'url' => '',
                "message" => 'Imagenes seleccionadas correctamente.'
            ];
        });
    }

    public function deleteImage($data)
    {
        $result = $this->builder->transaction(function ($db) use ($data) {

            $model = new Picture();
            $picture = $model->findWhere([
                'id' => $data['imageId'],
                'status' => 'disponible'
            ]);

            if (!$picture) {
                return [
                    'status' => 'fail',
                    'message' => 'Imagen no encontrada o ya fue seleccionada por el cliente.'
                ];
            }
            $result = $picture->remove();

            if (!$result) {
                return [
                    'status' => 'fail',
                    'message' => 'Error al eliminar la imagen.'
                ];
            }

            // Eliminar la imagen de la carpeta
            $imagePath = $data['dirName'] . '/' . $data['pictureName'];
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }

            // Eliminar la imagen de la carpeta de thumbnails
            $thumbnailPath = $data['tumbnailsDirname'] . '/' . $data['pictureName'];
            if (file_exists($thumbnailPath)) {
                @unlink($thumbnailPath);
            }

            // Eliminar la imagen dentro del zip
            $zipPath = $data['dirName'] . '/' . $data['zipName'];
            if (file_exists($zipPath)) {
                $zip = new ZipArchive();
                $zip->open($zipPath);
                $zip->deleteName($data['pictureName']);
                $zip->close();
            }

            return [
                'status' => 'success',
                'message' => 'Imagen eliminada exitosamente.'
            ];
        });
        return $result;
    }
}
