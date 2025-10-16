<form class="row needs-validation" id="myForm" novalidate>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1"><?= $nombreEvento ?></h4>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-4">
            <button type="submit" class="btn btn-label-info"
                data-bs-toggle="tooltip" data-bs-placement="top"
                data-bs-custom-class="custom-tooltip"
                data-bs-title="<?= $tooltipSaveBtn ?>">
                <i class="fas fa-user-plus mx-2"></i> Registrar
            </button>
            <?= $linkCancel ?>
        </div>
    </div>
    <div id="formResponses"></div>
    <div class="card mb-5">
        <div class="card-header d-flex align-items-center justify-content-between">
        </div>
        <div class="card-body">

            <?= $renderer->renderElement($form->getElement('project_id')) ?>
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
            <fieldset class="gap-5 mt-5">
                <legend style="font-size: 1.3em !important;">Información de Envio</legend>
                <div class="row">
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('nombre_contacto')) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('direccion_envio')) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('telefono_contacto')) ?>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</form>

<script>
    window.onload = function() {
        const form = document.getElementById("myForm");
        const viewElementResponse = document.getElementById("formResponses");
        const formHandler = new FormHandler('myForm', '/registrar-cliente-a-proyecto-action', (response) => {
            let alertType = "success";
            let icon = "far fa-check-circle";
            let msg = '';
            if (response.status === "success") {
                if (response.html) {
                    msg = response.html;
                } else {
                    msg = response.message;

                    setTimeout(function() {
                        window.location.href = response.url;
                    }, 1000);
                }
                const botonEnviar = document.querySelector('button[type="submit"]');
                botonEnviar.disabled = false;
            } else if (response.status === "fail") {
                alertType = "warning";
                icon = "fas fa-exclamation-triangle";
                msg = response.message;
                const botonEnviar = document.querySelector('button[type="submit"]');
                botonEnviar.disabled = false;
            } else {
                alertType = "danger";
                icon = "far fa-times-circle";
                msg = response.message;
            }
            let responseDiv = document.getElementById("formResponses");
            responseDiv.innerHTML = "";
            const alert = BootstrapAlertFactory.createAlert({
                message: msg,
                type: alertType,
                dismissible: true,
                icon: icon,
            });
            responseDiv.appendChild(alert.generateAlert());
        });
        formHandler.setViewElementResponse(viewElementResponse);
        formHandler.init();
    };
</script>