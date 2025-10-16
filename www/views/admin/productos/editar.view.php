<form class="row needs-validation" id="myForm" novalidate>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1"><?= $title ?></h4>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-4">
            <button type="submit" class="btn btn-primary"
                data-bs-toggle="tooltip" data-bs-placement="top"
                data-bs-custom-class="custom-tooltip"
                data-bs-title="<?= $tooltipSaveBtn ?>">
                <i class="far fa-edit mx-2"></i> Aceptar
            </button>
            <a class="btn btn-danger" href="<?= $linkCancelBtn ?>"
                data-bs-toggle="tooltip" data-bs-title="<?= $tooltipCancelBtn ?>">
                <i class="fas fa-times mx-2"></i> Cancelar
            </a>
        </div>
    </div>
    <div id="formResponses"></div>
    <div class="row p-0 m-0">
        <div class="col-sm-8">
            <div class="card border border-info overflow-hidden">
                <h5 class="card-header">
                    <i class="fas fa-box-open"></i> Detalles
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $renderer->renderElement($form->getElement('id')) ?>
                            <?= $renderer->renderElement($form->getElement('categoria_id')) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $renderer->renderElement($form->getElement('nombre')) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $renderer->renderElement($form->getElement('max_fotos')) ?>
                        </div>
                    </div>
                    <?= $renderer->renderElement($form->getElement('descripcion')) ?>
                    <?= $renderer->renderElement($form->getElement('status')) ?>

                </div>
                <div class="card-footer text-muted">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border border-success overflow-hidden">
                <h5 class="card-header">
                    <i class="fas fa-money-bill"></i> Precio
                </h5>
                <div class="card-body">
                    <?= $renderer->renderElement($form->getElement('precio')) ?>
                </div>
                <div class="card-footer text-muted">
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    window.onload = function() {
        const form = document.getElementById("myForm");
        const viewElementResponse = document.getElementById("formResponses");
        const formHandler = new FormHandler('myForm', '/editar-producto-form', (response) => {
            let alertType = "success";
            let icon = "far fa-check-circle";
            let msg = '';
            if (response.status === "success") {
                if (response.html) {
                    msg = response.html;
                } else {
                    msg = response.message;
                }
                const botonEnviar = document.querySelector('button[type="submit"]');
                botonEnviar.disabled = false;

                setTimeout(function() {
                    window.location.href = response.url;
                }, 1000);
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
            // let responseDiv = document.querySelector('.formResponses');

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