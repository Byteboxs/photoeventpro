<?php

namespace app\services\files;

class FileService
{
    private FileUploaderInterface $uploader;

    public function __construct(FileUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function upload(array $file): array
    {
        $result = $this->uploader->upload($file);
        return $result->toArray();
    }

    public function uploadMultiple(array $files): array
    {
        $result = $this->uploader->uploadMultiple($files);
        return $result->toArray();
    }

    public static function createDefault(array $config): self
    {
        // Crear estrategia de almacenamiento
        $storage = new LocalStorageStrategy($config['uploadPath']);

        // Crear cadena de validación
        $validator = ValidatorFactory::createDefaultValidationChain($config);

        // Crear generador de nombres
        $nameGenerator = new FileNameGenerator($config['filePrefix'] ?? '');

        // Crear uploader
        $uploader = new FileUploader($storage, $validator, $nameGenerator);

        // Crear y devolver el servicio
        return new self($uploader);
    }
}

// // Ejemplo completo de cómo procesar múltiples archivos de diferentes campos

// // Incluir las clases necesarias
// require_once 'vendor/autoload.php';

// use app\services\files\FileService;
// use app\services\files\utils\FileArrayUtil;

// // Configuración para diferentes tipos de archivos
// $imageConfig = [
//     'uploadPath' => '/var/www/uploads/images',
//     'maxFileSize' => 5 * 1024 * 1024, // 5MB
//     'allowedFileTypes' => ['image/jpeg', 'image/png', 'image/gif'],
//     'filePrefix' => 'img_',
//     'validateExtension' => true,
//     'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif']
// ];

// $documentConfig = [
//     'uploadPath' => '/var/www/uploads/documents',
//     'maxFileSize' => 20 * 1024 * 1024, // 20MB
//     'allowedFileTypes' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
//     'filePrefix' => 'doc_',
//     'validateExtension' => true,
//     'allowedExtensions' => ['pdf', 'doc', 'docx']
// ];

// // Crear servicios para diferentes tipos de archivos
// $imageService = FileService::createDefault($imageConfig);
// $documentService = FileService::createDefault($documentConfig);

// // Procesar la solicitud
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $results = [
//         'status' => 'success',
//         'message' => 'Procesamiento completado',
//         'details' => []
//     ];
    
//     // Procesar imágenes
//     if (isset($_FILES['images'])) {
//         // Normalizar el array de archivos
//         $normalizedFiles = FileArrayUtil::normalizeMultipleFilesArray($_FILES['images']);
        
//         // Subir cada archivo individualmente
//         foreach ($normalizedFiles as $file) {
//             $uploadResult = $imageService->upload($file);
//             $results['details']['images'][] = [
//                 'name' => $file['name'][0],
//                 'result' => $uploadResult
//             ];
            
//             // Si alguno falla, cambiar el estado general
//             if ($uploadResult['status'] === 'error') {
//                 $results['status'] = 'partial';
//             }
//         }
//     }
    
//     // Procesar documentos
//     if (isset($_FILES['documents'])) {
//         // Para documentos podemos usar directamente el método uploadMultiple
//         $uploadResult = $documentService->uploadMultiple($_FILES['documents']);
//         $results['details']['documents'] = $uploadResult;
        
//         // Si alguno falla, cambiar el estado general
//         if ($uploadResult['status'] === 'error') {
//             $results['status'] = 'partial';
//         }
//     }
    
//     // Devolver respuesta en formato JSON
//     header('Content-Type: application/json');
//     echo json_encode($results);
//     exit;
// }

// <!DOCTYPE html>
// <html lang="es">
// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Carga de Múltiples Archivos</title>
//     <style>
//         body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
//         .form-group { margin-bottom: 20px; }
//         label { display: block; margin-bottom: 5px; font-weight: bold; }
//         input[type="file"] { display: block; margin-bottom: 10px; }
//         button { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
//         .preview { margin-top: 20px; border: 1px solid #ddd; padding: 10px; display: none; }
//         .file-item { margin-bottom: 5px; }
//     </style>
// </head>
// <body>
//     <h1>Sistema de Carga de Archivos</h1>
    
//     <form action="" method="post" enctype="multipart/form-data" id="uploadForm">
//         <div class="form-group">
//             <label for="images">Imágenes (JPG, PNG, GIF - máx. 5MB):</label>
//             <input type="file" name="images[]" id="images" accept="image/*" multiple>
//         </div>
        
//         <div class="form-group">
//             <label for="documents">Documentos (PDF, DOC, DOCX - máx. 20MB):</label>
//             <input type="file" name="documents[]" id="documents" accept=".pdf,.doc,.docx" multiple>
//         </div>
        
//         <button type="submit">Subir Archivos</button>
//     </form>
    
//     <div id="preview" class="preview">
//         <h2>Archivos seleccionados:</h2>
//         <div id="fileList"></div>
//     </div>
    
//     <div id="results" class="preview">
//         <h2>Resultados:</h2>
//         <pre id="resultContent"></pre>
//     </div>
    
//     <script>
//         // Script para mostrar una previsualización de los archivos seleccionados
//         document.addEventListener('DOMContentLoaded', function() {
//             const fileInputs = document.querySelectorAll('input[type="file"]');
//             const preview = document.getElementById('preview');
//             const fileList = document.getElementById('fileList');
//             const uploadForm = document.getElementById('uploadForm');
//             const results = document.getElementById('results');
//             const resultContent = document.getElementById('resultContent');
            
//             // Mostrar archivos seleccionados
//             fileInputs.forEach(input => {
//                 input.addEventListener('change', function() {
//                     updateFileList();
//                 });
//             });
            
//             // Actualizar lista de archivos
//             function updateFileList() {
//                 fileList.innerHTML = '';
//                 let hasFiles = false;
                
//                 fileInputs.forEach(input => {
//                     if (input.files.length > 0) {
//                         hasFiles = true;
//                         const fieldset = document.createElement('fieldset');
//                         const legend = document.createElement('legend');
//                         legend.textContent = input.previousElementSibling.textContent;
//                         fieldset.appendChild(legend);
                        
//                         for (let i = 0; i < input.files.length; i++) {
//                             const file = input.files[i];
//                             const item = document.createElement('div');
//                             item.className = 'file-item';
//                             item.textContent = `${file.name} - ${formatFileSize(file.size)}`;
//                             fieldset.appendChild(item);
//                         }
                        
//                         fileList.appendChild(fieldset);
//                     }
//                 });
                
//                 preview.style.display = hasFiles ? 'block' : 'none';
//             }
            
//             // Formatear tamaño de archivo
//             function formatFileSize(bytes) {
//                 if (bytes < 1024) return bytes + ' bytes';
//                 else if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' KB';
//                 else return (bytes / 1048576).toFixed(2) + ' MB';
//             }
            
//             // Enviar formulario con AJAX
//             uploadForm.addEventListener('submit', function(e) {
//                 e.preventDefault();
                
//                 const formData = new FormData(uploadForm);
                
//                 fetch(uploadForm.action, {
//                     method: 'POST',
//                     body: formData
//                 })
//                 .then(response => response.json())
//                 .then(data => {
//                     results.style.display = 'block';
//                     resultContent.textContent = JSON.stringify(data, null, 2);
//                 })
//                 .catch(error => {
//                     results.style.display = 'block';
//                     resultContent.textContent = 'Error: ' + error.message;
//                 });
//             });
//         });
//     </script>
// </body>
// </html>