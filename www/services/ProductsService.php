<?php

namespace app\services;

use app\core\Application;
use app\core\database\builder\Builder;
use app\core\Singleton;
use app\models\Project;
use app\models\Service;
use app\services\files\FileService;
use app\services\files\FileUploader;
use app\services\files\FileUploaderConfig;
use app\services\files\FileValidator;

class ProductsService extends Singleton
{
    private Builder $builder;
    protected function __construct()
    {
        parent::__construct();
        $this->builder = Application::$app->builder;
    }

    public function create(array $args)
    {
        return $this->builder->transaction(function ($db) use ($args) {
            $data = $args['form'];

            // Configuración
            $config = [
                'uploadPath' => $args['uploadPath'],
                'maxFileSize' => (1048576 * 0.5), // 0.5 MiB
                'allowedFileTypes' => ['image/jpeg', 'image/png'],
                'filePrefix' => 'product_',
                'validateExtension' => true,
                'allowedExtensions' => ['jpg', 'jpeg', 'png']
            ];

            $noImage = true;
            $filePath = 'service-default-3.webp';
            if (isset($args['files']['files'])) {
                $noImage = false;
            }
            if (!$noImage) {
                $fileService = FileService::createDefault($config);
                $uploadResult = $fileService->upload($args['files']['files']);
                if ($uploadResult['status'] === 'error') {
                    return [
                        'status' => 'fail',
                        'message' => $uploadResult['message']
                    ];
                }
                $filePath = $uploadResult['data']['file_path'];
            }

            $model = new Service();
            $model->categoria_id = $data['categoria_id'];
            $model->nombre = $data['nombre'];
            $model->descripcion = $data['descripcion'];
            $model->precio = $data['precio'];
            $model->max_fotos = $data['max_fotos'];
            $model->min_fotos = 1;
            $model->image = $filePath;
            $saveResult = $model->save();
            if (!$saveResult) {
                unlink($filePath);
                return [
                    'status' => 'fail',
                    'message' => 'Error al crear el servicio.'
                ];
            }
            return [
                'status' => 'success',
                'url' => APP_DIRECTORY_PATH . '/listado-de-productos',
                "message" => 'Pedido de servicios creado exitosamente.'
            ];
        });
    }
    public function update(array $args)
    {
        return $this->builder->transaction(function ($db) use ($args) {
            // Configuración
            $config = [
                'uploadPath' => $args['uploadPath'],
                'maxFileSize' => 524288, // 0.5 MiB (512 KB)
                'allowedFileTypes' => ['image/jpeg', 'image/png'],
                'filePrefix' => 'product_',
                'validateExtension' => true,
                'allowedExtensions' => ['jpg', 'jpeg', 'png']
            ];

            // Crear servicio usando la fachada
            $fileService = FileService::createDefault($config);

            // Uso simple
            $result = $fileService->upload($args['files']['files']);
            return $result;

            // $imageConfig = new FileUploaderConfig(
            //     $args['uploadPath'], // Ruta para imágenes
            //     524288, // 0.5 MiB (512 KB)
            //     ['image/jpeg', 'image/png', 'image/gif'] // Tipos de imagen permitidos
            // );
            // $imageValidator = new FileValidator($imageConfig);
            // $imageUploader = new FileUploader($imageConfig, $imageValidator);
            // return $imageUploader->upload($args['image']);


            // $model = new Service();
            // $model->id = $args['id'];
            // $model->categoria_id = $args['categoria_id'];
            // $model->nombre = $args['nombre'];
            // $model->descripcion = $args['descripcion'];
            // $model->precio = $args['precio'];
            // $model->max_fotos = $args['max_fotos'];
            // $model->min_fotos = 1;
            // if (isset($args['status'])) {
            //     $model->status = 'activo';
            // } else {
            //     $model->status = 'inactivo';
            // }
            // return $model->save();
            // return [
            //     'status' => 'fail',
            //     'message' => 'No implementado',
            // ];
        });
    }
}
