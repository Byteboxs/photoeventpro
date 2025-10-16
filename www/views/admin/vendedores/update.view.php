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
                <?= $btnUpdate ?>
                <?= $btnCancelar ?>
            </div>
        </div>
    </div>
    <div class="formResponses"></div>

    <div class="card mb-6">
        <h5 class="card-header"></h5>
        <div class="card-body">
            <fieldset class="gap-5">
                <legend style="font-size: 1.3em !important;">Datos personales</legend>
                <div class="row">
                    <div class="col-md-3">
                        <?= $renderer->renderElement($form->getElement('user_id')) ?>
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
            <fieldset class="gap-5 mt-5">
                <legend style="font-size: 1.3em !important;">Activar o inactivar</legend>
                <div class="row">
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('status')) ?>
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                </div>
            </fieldset>
        </div>
        <hr>
        <div class="formResponses"></div>
        <div class="card-footer text-body-secondary">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center"></div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                        <?= $btnUpdate ?>
                        <?= $btnCancelar ?>
                    </div>
                </div>
            </div>
        </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const decoder = new Base64Decoder("trash");
        let apiUrl = decoder.decode() + '/vendedores/vendedor/update';
        console.log(apiUrl);
        const form = document.getElementById('myForm');

        const statusCheckbox = document.getElementById('status');

        statusCheckbox.addEventListener('change', function() {

            if (this.checked) {
                this.value = 'activo';
                this.checked = true;
                this.setAttribute('checked', '');
            } else {
                this.value = 'inactivo';
                this.checked = false;
                this.removeAttribute('checked');
            }
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const validator = new FieldValidator(form);
            const formDataExtractor = new FormDataExtractor(form, {
                excludedFields: [] // Opcional: campos a excluir
            });
            let data = formDataExtractor.extract();
            console.log(data);
            if (validator.validateForm()) {
                const myAjax = new ModernAjaxHandler({
                    timeout: 10000, // Timeout global de 10 segundos (opcional)
                });
                myAjax.post(apiUrl, data, {
                        headers: {
                            // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
                        }
                    })
                    .then(response => {
                        // let responseDiv = document.getElementById("formResponses");
                        let responseDivs = document.querySelectorAll(".formResponses");
                        setTimeout(() => {
                            if (typeof response === 'object') {

                                if (response.success) {
                                    responseDivs.forEach(responseDiv => {
                                        responseDiv.innerHTML = "";
                                        const alert = BootstrapAlertFactory.createAlert({
                                            message: response.message,
                                            type: "success",
                                            dismissible: true,
                                            icon: "far fa-check-circle",
                                        });
                                        responseDiv.appendChild(alert.generateAlert());
                                    });
                                } else {
                                    responseDivs.forEach(responseDiv => {
                                        responseDiv.innerHTML = "";
                                        const alert = BootstrapAlertFactory.createAlert({
                                            message: response.message,
                                            type: "success",
                                            dismissible: true,
                                            icon: "far fa-check-circle",
                                        });
                                        responseDiv.appendChild(alert.generateAlert());
                                    });
                                }
                            } else {
                                const alert = BootstrapAlertFactory.createAlert({
                                    message: response,
                                    type: "warning",
                                    dismissible: true,
                                    icon: "far fa-check-circle",
                                });
                                responseDiv.innerHTML = "";
                                responseDiv.appendChild(alert.generateAlert());
                            }
                        }, 1000);
                    })
                    .catch(error => {
                        console.error('Error al subir el archivo:', error);
                    });
            }

        });
    });
</script>