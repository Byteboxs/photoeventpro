<style>
    #drop-area {
        border: 2px dashed #ccc;
        border-radius: 20px;
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        cursor: pointer;
    }

    #drop-area.highlight {
        border-color: #007bff;
    }

    #image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        /* Espacio entre las imágenes en la vista previa */
        padding: 5px;
        /* Espacio interno alrededor de las imágenes en la vista previa */
    }

    #image-preview img {
        /* Estilos base para cada imagen */
        width: 100px;
        /* Ancho fijo para las imágenes */
        height: 100px;
        /* Alto fijo para las imágenes - para mantener la forma cuadrada o rectangular deseada */
        object-fit: cover;
        /*  Asegura que la imagen cubra el contenedor, recortando si es necesario para mantener la proporción */
        border-radius: 8px;
        /* Bordes redondeados para las imágenes */
        border: 2px solid #ddd;
        /*  Borde sutil para separar las imágenes del fondo */
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        /*  Sombra suave para dar un poco de relieve */
        margin: 0;
        /* Elimina el margen predeterminado si ya tienes espaciado con 'gap' en #image-preview */
    }

    #image-preview img:hover {
        /*  Efecto al pasar el ratón por encima (opcional) */
        box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.2);
        /*  Sombra más pronunciada al pasar el ratón */
        transform: scale(1.05);
        /*  Ligeramente más grande al pasar el ratón */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        /*  Animación suave para la escala y la sombra */
    }
</style>
<form class="row needs-validation" id="myForm" novalidate enctype="multipart/form-data">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1">Nuevo producto</h4>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-4">
            <div class="d-flex gap-4">
                <button type="submit" class="btn btn-outline-primary form-control my-2"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    data-bs-custom-class="custom-tooltip"
                    data-bs-title="<?= $tooltipSaveBtn ?>">
                    <i class="fas fa-plus mx-2"></i> Crear
                </button>
                <a class="btn btn-outline-danger form-control my-2" href="<?= $linkCancelBtn ?>"
                    data-bs-toggle="tooltip" data-bs-title="<?= $tooltipCancelBtn ?>">
                    <i class="fas fa-times mx-2"></i> Cancelar
                </a>
            </div>
        </div>
    </div>
    <div id="formResponses" class="mt-3"></div>
    <div class="card mb-5">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- <div class="card-body"> -->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $renderer->renderElement($form->getElement('categoria_id')) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $renderer->renderElement($form->getElement('nombre')) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $renderer->renderElement($form->getElement('precio')) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $renderer->renderElement($form->getElement('max_fotos')) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $renderer->renderElement($form->getElement('descripcion')) ?>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
                <div class="col-md-6">
                    <div id="file-upload-container" class="file-drop-area-container"></div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    window.onload = function() {
        // const form = document.getElementById("myForm");
        // const viewElementResponse = document.getElementById("formResponses");
        // const formHandler = new FormHandler('myForm', '/crear-producto-form', (response) => {
        //     let alertType = "success";
        //     let icon = "far fa-check-circle";
        //     let msg = '';
        //     if (response.status === "success") {
        //         if (response.html) {
        //             msg = response.html;
        //         } else {
        //             msg = response.message;
        //         }
        //         const botonEnviar = document.querySelector('button[type="submit"]');
        //         botonEnviar.disabled = false;

        //         setTimeout(function() {
        //             window.location.href = response.url;
        //         }, 1000);
        //     } else if (response.status === "fail") {
        //         alertType = "warning";
        //         icon = "fas fa-exclamation-triangle";
        //         msg = response.message;
        //         const botonEnviar = document.querySelector('button[type="submit"]');
        //         botonEnviar.disabled = false;
        //     } else {
        //         alertType = "danger";
        //         icon = "far fa-times-circle";
        //         msg = response.message;
        //     }
        //     let responseDiv = document.getElementById("formResponses");
        //     responseDiv.innerHTML = "";
        //     const alert = BootstrapAlertFactory.createAlert({
        //         message: msg,
        //         type: alertType,
        //         dismissible: true,
        //         icon: icon,
        //     });
        //     responseDiv.appendChild(alert.generateAlert());
        // });
        // formHandler.setViewElementResponse(viewElementResponse);
        // formHandler.init();


        let maxFileSize = (1048576 * 0.5);

        const fileUploadContainer = document.getElementById('file-upload-container');
        const fileDragAndDropHandler = new FileDragAndDropHandler('file-upload-container', {
            multiple: false,
            maxFileSize: maxFileSize,
            previewPosition: 'right', // Previsualización a la DERECHA
            acceptedFileTypes: 'image/*',
            customStyles: `
            .file-drop-area-container {
                display: flex;
                flex-direction: column;
                height: 100%;
                width: 100%;
            }
            .file-drop-area {
                height: 100%; 
            }
            .file-drop-area.highlight {
                border-color: #9b59b6;
            }
            .file-preview-area {
                margin-left: 1.25rem !important;
                margin-top: 1px;
                display: flex;
                flex-direction: column;
                min-height: 100%;
                overflow: auto;
            }
            .file-preview-item {
                // border-color: #d35400;
                max-height: 100%;
                
            }
            .file-preview-item img {
                max-height: 100%;
                border-radius: 6px;
            }
        `,
            additionalAcceptedMimeTypes: ['image/jpeg', 'image/png', 'image/gif'] // <----  Define los tipos MIME aquí como un ARRAY de strings
        });

        const decoder = new Base64Decoder("trash");
        let apiUrl = decoder.decode() + '/crear-producto-form';
        console.log(apiUrl);

        const form = document.getElementById('myForm');
        // Initialize for multiple images upload (default)
        // const multipleImageHandler = new ImageDragAndDropHandler('drop-area', 'images', 'image-preview');
        // To initialize for single image upload, use:
        // const singleImageHandler = new ImageDragAndDropHandler('drop-area', 'images', 'image-preview', false);
        const validator = new FieldValidator(form);
        const formDataExtractor = new FormDataExtractor(form, {
            excludedFields: [] // Opcional: campos a excluir
        });

        // Configuración para FormEventHandler
        const eventHandlerConfig = {
            eventType: 'submit', // o 'click' para manejar el evento click de un botón, etc.
            apiEndpoint: apiUrl, // **Reemplaza con tu URL de API real**
            onSuccess: function(responseData) {
                let responseDiv = document.getElementById("formResponses");
                responseDiv.innerHTML = "";
                if (typeof responseData === 'object') {
                    if (responseData.status === "success") {
                        const alert = BootstrapAlertFactory.createAlert({
                            message: responseData.message,
                            type: "success",
                            dismissible: true,
                            icon: "far fa-check-circle",
                        });
                        responseDiv.appendChild(alert.generateAlert());

                        setTimeout(function() {
                            window.location.href = responseData.url;
                        }, 2000);
                    } else {
                        const alert = BootstrapAlertFactory.createAlert({
                            message: responseData.message,
                            type: "danger",
                            dismissible: true,
                            icon: "far fa-times-circle",
                        });
                        responseDiv.appendChild(alert.generateAlert());
                    }
                } else {
                    const alert = BootstrapAlertFactory.createAlert({
                        message: responseData,
                        type: "success",
                        dismissible: true,
                        icon: "far fa-check-circle",
                    });
                    responseDiv.appendChild(alert.generateAlert());
                }
            },
            onError: function(error) {
                console.error('Error en el envío:', error);
            },
            onValidationError: function(extractor) {
                console.warn('Errores de validación detectados.');
                let responseDiv = document.getElementById("formResponses");
                responseDiv.innerHTML = "";
                const alert = BootstrapAlertFactory.createAlert({
                    message: "Errores de validación detectados.",
                    type: "warning",
                    dismissible: true,
                    icon: "fas fa-exclamation-triangle",
                });
                responseDiv.appendChild(alert.generateAlert());
            },
            onSubmitStart: function(formData) {
                console.log('Iniciando envío del formulario...');
            }
        };

        // Crear instancia de FormEventHandler
        const formEventHandler = new FormEventHandler(form, validator, formDataExtractor, eventHandlerConfig);

        // Ahora, el evento 'submit' (o el tipo configurado) en el formulario 'myForm' será manejado por FormEventHandler

    };
</script>