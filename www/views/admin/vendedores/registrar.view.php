<style>
    .text-body-secondary {
        --bs-text-opacity: 1;
        color: var(--bs-secondary-color) !important;
    }
</style>
<form class="row needs-validation" id="myForm" novalidate>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1"><?= $title ?></h4>
            <p class="mb-0"><?= $subtitle ?></p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-4">
            <div class="d-flex gap-4">
                <?= $btnRegistrar ?>
                <?= $btnCancelar ?>
            </div>
        </div>
    </div>

    <div id="formResponses"></div>

    <div class="card mb-6">
        <h5 class="card-header"></h5>
        <div class="card-body">
            <fieldset class="gap-5">
                <legend style="font-size: 1.3em !important;">Datos personales</legend>
                <div class="row">
                    <div class="col-md-3">
                        <?= $renderer->renderElement($form->getElement('primer_nombre')) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $renderer->renderElement($form->getElement('segundo_nombre')) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $renderer->renderElement($form->getElement('primer_apellido')) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $renderer->renderElement($form->getElement('segundo_apellido')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $renderer->renderElement($form->getElement('document_type_id')) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $renderer->renderElement($form->getElement('numero_identificacion')) ?>
                    </div>
                </div>
            </fieldset>
            <fieldset class="gap-5 mt-5">
                <legend style="font-size: 1.3em !important;">Datos de contacto</legend>
                <div class="row">
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('email')) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('direccion')) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('telefono')) ?>
                    </div>
                </div>
            </fieldset>
        </div>
        <hr>
        <div class="card-footer text-body-secondary">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center"></div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                        <?= $btnRegistrar ?>
                        <?= $btnCancelar ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    window.onload = function() {
        const decoder = new Base64Decoder("trash");
        let apiUrl = decoder.decode() + '/vendedores/registrar/save';
        console.log(apiUrl);

        const form = document.getElementById('myForm');
        const validator = new FieldValidator(form);
        const formDataExtractor = new FormDataExtractor(form, {
            excludedFields: [] // Opcional: campos a excluir
        });

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