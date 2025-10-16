<div class="card mb-5">
    <form class="row needs-validation" id="myForm" novalidate>
        <div class="card-body">
            <fieldset>
                <legend>Datos principales</legend>
                <div class="row">
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('nombre_evento')) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('fecha_inicio')) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $renderer->renderElement($form->getElement('hora_ceremonia')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $renderer->renderElement($form->getElement('descripcion')) ?>
                    </div>
                </div>
            </fieldset>
            <fieldset class="my-5">
                <legend>Instituciòn</legend>
                <div class="row">
                    <div class="col-md-4">
                        <div class="select-con-buscador">
                            <?= $renderer->renderElement($form->getElement('institution_id')) ?>
                            <div class="search-overlay">
                                <input type="text" class="form-control search-input rounded-0 border-0 shadow-sm" placeholder="Buscar...">
                                <div class="options-container">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="formulario_institucion" style="display: none;">
                        <?= $renderer->renderElement($form->getElement('nombre_institucion')) ?>
                    </div>
                </div>
            </fieldset>
            <fieldset class="my-5">
                <legend>Ubicación</legend>
                <div class="row">
                    <div class="col-md-4">
                        <div class="select-con-buscador">
                            <?= $renderer->renderElement($form->getElement('location_id')) ?>
                            <div class="search-overlay">
                                <input type="text" class="form-control search-input form-control search-input rounded-0 border-0 shadow-sm" placeholder="Buscar...">
                                <div class="options-container">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="divNombre" style="display: none;">
                        <?= $renderer->renderElement($form->getElement('nombre_locacion')) ?>
                    </div>
                    <div class="col-md-4" id="divDireccion" style="display: none;">
                        <?= $renderer->renderElement($form->getElement('direccion')) ?>
                    </div>
                </div>
            </fieldset>
            <div class="row my-4">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-outline-primary form-control my-2"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="custom-tooltip"
                        data-bs-title="Haga click para crear un nuevo proyecto">
                        <i class="fas fa-plus mx-2"></i> Enviar
                    </button>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-outline-danger form-control my-2" href="<?= $linkCancelBtn ?>"
                        data-bs-toggle="tooltip" data-bs-title="Cancelar la creación del proyecto">
                        <i class="fas fa-times mx-2"></i> Cancelar
                    </a>
                </div>
            </div>

            <div id="formResponses"></div>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const miSelect = document.getElementById('institution_id');
        if (miSelect) new SelectConBuscador(miSelect);
        const otroSelect = document.getElementById('location_id');
        if (otroSelect) new SelectConBuscador(otroSelect);
    });

    const institucionSelect = document.getElementById('institution_id');
    const formularioInstitucion = document.getElementById('formulario_institucion');

    institucionSelect.addEventListener('change', () => {
        const nombreInstitucion = document.getElementById('nombre_institucion');
        if (institucionSelect.value === 'crear') {
            formularioInstitucion.style.display = 'block';
            nombreInstitucion.classList.remove('no_validate');
        } else {
            formularioInstitucion.style.display = 'none';
            nombreInstitucion.classList.add('no_validate');
        }
    });

    const locacionSelect = document.getElementById('location_id');
    const divNombre = document.getElementById('divNombre');
    const divDireccion = document.getElementById('divDireccion');

    locacionSelect.addEventListener('change', () => {
        const nombreLocacion = document.getElementById('nombre_locacion');
        const direccion = document.getElementById('direccion');
        if (locacionSelect.value === 'crear') {
            divNombre.style.display = 'block';
            divDireccion.style.display = 'block';
            nombreLocacion.classList.remove('no_validate');
            direccion.classList.remove('no_validate');
        } else {
            divNombre.style.display = 'none';
            divDireccion.style.display = 'none';
            nombreLocacion.classList.add('no_validate');
            direccion.classList.add('no_validate');
        }
    });

    window.onload = function() {
        const form = document.getElementById("myForm");
        const viewElementResponse = document.getElementById("formResponses");
        const formHandler = new FormHandler('myForm', '/crear-proyecto-form', (response) => {
            let alertType = "success";
            let icon = "far fa-check-circle";
            let msg = '';
            if (response.status === "success") {
                console.log(response);
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