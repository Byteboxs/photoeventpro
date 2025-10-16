<style>
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 1086;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .overlay.show {
        opacity: 1;
    }

    .overlay-image {
        /* max-width: 95%; */
        max-height: 90%;
        object-fit: contain;
        transform: scale(0.9);
        transition: transform 0.3s ease-in-out;
    }

    .overlay.show .overlay-image {
        transform: scale(1);
    }

    .close-button {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 30px;
        color: white;
        cursor: pointer;
    }

    .img-thumbnail {
        margin: 5px;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .img-thumbnail:hover {
        transform: scale(1.05);
    }

    .arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 30px;
        color: white;
        cursor: pointer;
        padding: 10px;
        transition: transform 0.2s ease-in-out;
    }

    .arrow:hover {
        transform: translateY(-50%) scale(1.1);
    }

    .arrow-left {
        left: 20px;
    }

    .arrow-right {
        right: 20px;
    }

    .overlay-image.fade-in {
        animation: fade-in 0.3s ease-in-out;
    }

    @keyframes fade-in {
        from {
            opacity: 0.7;
        }

        to {
            opacity: 1;
        }
    }


    .image-gallery-masonry-css {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        /* Ancho mínimo de columna */
        grid-gap: 5px;
        /* Espacio entre elementos */
        grid-template-rows: masonry;
        /* Habilita el layout masonry */
        masonry-auto-flow: ordered;
        /* Orden de llenado de columnas */
    }

    .image-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .image-container img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 5px;
        /* Espacio para el botón */
    }

    .image-button {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-top: 5px;
    }

    .image-gallery-masonry-css img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 0;
        /* Importante para masonry */
    }

    @media (max-width: 768px) {
        .image-container img {
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .image-gallery-masonry-css {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            /* Columnas más estrechas en móviles */
            grid-gap: 8px;
        }

        .image-gallery-masonry-css img {
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    }

    /* Estilos del banner */
    .user-profile-header-banner {
        position: relative;
        height: 150px;
        width: 100%;
        overflow: hidden;
    }

    .user-profile-header-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Si quieres reemplazar la imagen del banner con el gradiente colorido */
    /* .user-profile-header-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg,
                rgba(78, 194, 202, 1) 0%,
                rgba(184, 233, 227, 1) 33%,
                rgba(253, 242, 236, 1) 66%,
                rgba(255, 175, 175, 1) 100%);
        z-index: 1;
    } */

    /* Ocultar la imagen original si usas el gradiente */
    /* .user-profile-header-banner img {
        opacity: 0;
    } */

    /* Estilos para la sección de información del usuario */
    .user-profile-header {
        position: relative;
        padding: 0 20px 20px;
    }

    /* Estilos para el avatar con iniciales */
    .avatar-initial {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 500;
        color: #fff;
        background-color: #28c76f;
        border: 5px solid;
        border-top-color: currentcolor;
        border-right-color: currentcolor;
        border-bottom-color: currentcolor;
        border-left-color: currentcolor;
        border-color: #2b2c40;
        margin-top: -50px;
        position: relative;
        z-index: 2;
    }

    /* Color de fondo para el avatar con la clase bg-label-success */
    /* .bg-label-success {
        background-color: rgba(40, 199, 111, 0.2) !important;
        color: #28c76f !important;
    } */

    /* Estilo para hacer el avatar circular */
    /* .rounded-circle {
        border-radius: 50% !important;
    } */

    /* Estilos para la imagen de perfil */
    .user-profile-img {
        width: 90px;
        height: 90px;
        background-color: var(--bs-heading-color);
        border: 5px solid;
        border-top-color: currentcolor;
        border-right-color: currentcolor;
        border-bottom-color: currentcolor;
        border-left-color: currentcolor;
        border-color: #2b2c40;
        margin-top: -50px;
        position: relative;
        z-index: 2;
    }

    /* Ajustes para información del perfil */
    .user-profile-info {
        margin-left: 0;
    }

    @media (min-width: 576px) {
        .user-profile-info {
            margin-left: 20px;
        }
    }

    /* Estilos para el nombre del usuario */
    .user-profile-info h4 {
        color: var(--bs-heading-color);
        font-weight: 500;
        font-size: 24px;
    }

    /* Estilos para la lista de información */
    .list-inline {
        padding-left: 0;
        list-style: none;
    }

    .list-inline .list-inline-item {
        color: #666;
        font-size: 14px;
    }

    .icon-base {
        font-size: 16px;
        vertical-align: middle;
    }

    .product-card {
        height: 100%;
        transition: all 0.3s;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .product-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .product-img-container {
        position: relative;
        overflow: hidden;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        /* background-color: var(--bs-dark); */
    }

    .product-img {
        /*       max-height: 100%; */
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .product-card:hover .product-img {
        -webkit-animation: flip-2-ver-right-1 0.5s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
        animation: flip-2-ver-right-1 0.5s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
    }

    .product-info {
        padding: 15px;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        height: 2.4rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #28a745;
    }

    .product-rating {
        margin: 10px 0;
        color: #ffc107;
    }

    .product-description {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 10px;
        height: 3.6rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        max-width: 120px;
    }

    .quantity-btn {
        background-color: #e9ecef;
        border: none;
        width: 30px;
        height: 30px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
    }

    .quantity-input {
        width: 40px;
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 4px;
        margin: 0 5px;
    }

    /* Animación proporcionada */
    .flip-2-ver-right-1 {
        -webkit-animation: flip-2-ver-right-1 0.5s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
        animation: flip-2-ver-right-1 0.5s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
    }

    @-webkit-keyframes flip-2-ver-right-1 {
        0% {
            -webkit-transform: translateX(0) rotateY(0);
            transform: translateX(0) rotateY(0);
            -webkit-transform-origin: 100% 50%;
            transform-origin: 100% 50%;
        }

        100% {
            -webkit-transform: translateX(100%) rotateY(-180deg);
            transform: translateX(100%) rotateY(-180deg);
            -webkit-transform-origin: 0% 50%;
            transform-origin: 0% 50%;
        }
    }

    @keyframes flip-2-ver-right-1 {
        0% {
            -webkit-transform: translateX(0) rotateY(0);
            transform: translateX(0) rotateY(0);
            -webkit-transform-origin: 100% 50%;
            transform-origin: 100% 50%;
        }

        100% {
            -webkit-transform: translateX(100%) rotateY(-180deg);
            transform: translateX(100%) rotateY(-180deg);
            -webkit-transform-origin: 0% 50%;
            transform-origin: 0% 50%;
        }
    }

    /* Añadimos un botón de acción */
    .add-to-cart-btn {
        width: 100%;
        border-radius: 5px;
        font-weight: 500;
    }
</style>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"></h4>
    </div>

</div>
<div class="row">
    <div class="col-12">
        <div class="card mb-6 rounded-top">
            <div class="user-profile-header-banner">
                <img src="<?= $banner ?>profile-banner.png" alt="Banner image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-8">
                <div class="flex-shrink-0 mt-1 mx-sm-0 mx-auto">
                    <!-- <img src="<?= $banner ?>avatar_robot.png" alt="user image" class="d-block h-auto ms-0 ms-sm-6 rounded-3 user-profile-img"> -->
                    <span class="avatar-initial rounded-circle bg-label-success ">AO</span>


                </div>
                <div class="flex-grow-1 mt-3 mt-lg-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-2 mt-lg-7"><?= $customerName ?></h4>
                        </div>
                        <div class="d-flex align-content-center flex-wrap gap-4">
                            <div class="d-flex gap-4">
                                <?= $linkPagoEfectivo ?>
                                <?= $linkCargarImagenes ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-5">
        <div class="card mb-6">
            <div class="card-body">
                <small class="card-text text-uppercase text-body-secondary small">Acerca de</small>
                <ul class="list-unstyled my-3 py-1">
                    <li class="d-flex align-items-center mb-4">
                        <i class="icon-base bx bx-user"></i>
                        <span class="fw-medium mx-2">Nombre:</span>
                        <span><?= $customerName ?></span>
                    </li>
                    <!-- <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-check"></i><span class="fw-medium mx-2">Status:</span> <span>Active</span></li> -->
                    <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-crown"></i><span class="fw-medium mx-2">Rol:</span> <span>Cliente</span></li>
                    <li class="d-flex align-items-center mb-4">
                        <i class="icon-base bx bx-flag"></i>
                        <span class="fw-medium mx-2"><?= $info->tipo_documento ?>:</span>
                        <span><?= $info->numero_documento ?></span>
                    </li>
                </ul>
                <small class="card-text text-uppercase text-body-secondary small">Contacto</small>
                <ul class="list-unstyled my-3 py-1">
                    <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-phone"></i><span class="fw-medium mx-2">Tel:</span> <span><?= $info->telefono_usuario ?></span></li>
                    <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-envelope"></i><span class="fw-medium mx-2">Email:</span> <span><?= $info->email_usuario ?></span></li>
                    <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-map"></i><span class="fw-medium mx-2">Dirección:</span> <span><?= $info->direccion_residencia ?></span></li>
                </ul>
                <small class="card-text text-uppercase text-body-secondary small">Información de envío</small>
                <ul class="list-unstyled mb-0 mt-3 pt-1">
                    <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-phone"></i><span class="fw-medium mx-2">Contacto:</span> <span><?= $info->nombre_contacto ?></span></li>
                    <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-map"></i><span class="fw-medium mx-2">Enviar a:</span> <span><?= $info->direccion_envio ?></span></li>
                    <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-phone"></i><span class="fw-medium mx-2">Tel Contacto:</span> <span><?= $info->telefono_usuario ?></span></li>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-7 col-md-7">
        <div class="card mb-6">
            <div class="card-body">
                <small class="card-text text-uppercase text-body-secondary small">Imagenes</small>
                <div class="image-gallery-masonry-css mt-5">
                    <?php if ($hasImages) { ?>
                        <?php foreach ($imagenes as $imagen) { ?>
                            <div class="image-container">
                                <?= $imagen['image'] ?>
                                <div class="image-button">
                                    <?= $imagen['button'] ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Atención</h4>
                            <p>No hay imagenes cargadas</p>
                        </div>
                    <?php } ?>
                </div>
                <div id="formResponses" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <small class="card-text text-uppercase text-body-secondary small">Servicios Seleccionados</small>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 mt-3">

        </div>
        <div class="table-responsive" id="contenedorTabla" style="max-height: 42vh; overflow: auto;">
            <?= $table->render(); ?>
        </div>
    </div>
</div>
<div class="overlay" id="overlay">
    <span class="close-button" id="closeButton">&times;</span>
    <span class="arrow arrow-left" id="arrowLeft">&#10094;</span>
    <img class="overlay-image" id="overlayImage">
    <span class="arrow arrow-right" id="arrowRight">&#10095;</span>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // const images = document.querySelectorAll('.image-gallery img');
        const deleteButtons = document.querySelectorAll('.delete-image-button');
        const images = document.querySelectorAll('.image-gallery-masonry-css img');
        const overlay = document.getElementById('overlay');
        const overlayImage = document.getElementById('overlayImage');
        const closeButton = document.getElementById('closeButton');
        const arrowLeft = document.getElementById('arrowLeft');
        const arrowRight = document.getElementById('arrowRight');
        let currentImageIndex = 0;

        images.forEach((image, index) => {
            image.addEventListener('click', function() {
                overlay.style.display = 'flex';
                overlay.classList.add('show');
                overlayImage.src = this.src;
                currentImageIndex = index;
            });
        });

        closeButton.addEventListener('click', function() {
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300); // Esperar a que termine la transición
        });

        arrowLeft.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            overlayImage.classList.add('fade-in');
            overlayImage.src = images[currentImageIndex].src;
            setTimeout(() => {
                overlayImage.classList.remove('fade-in');
            }, 300);
        });

        arrowRight.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            overlayImage.classList.add('fade-in');
            overlayImage.src = images[currentImageIndex].src;
            setTimeout(() => {
                overlayImage.classList.remove('fade-in');
            }, 300);
        });

        // Asegúrate de que 'AjaxHandler' y 'elemento' con 'data-valor-base-64' están definidos en tu contexto.
        // Este es un ejemplo y necesitarás adaptarlo a tu configuración específica.
        const elemento = document.getElementById('trash'); // Reemplaza 'trash' con el ID de tu elemento si es diferente
        const valorBase64 = elemento.getAttribute('data-valor-base64');
        // Asumiendo que AjaxHandler.decodificarBase64 es una función global accesible
        const url = typeof AjaxHandler !== 'undefined' && AjaxHandler.decodificarBase64 ? AjaxHandler.decodificarBase64(valorBase64) : '/api'; // Usar '/api' como fallback si AjaxHandler no está definido
        console.log("URL base decodificada:", url);
        const uploadUrl = url + '/eventos/clientes/eliminar-imagen-almacenada';
        const formResponsesDiv = document.getElementById('formResponses');


        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation(); // Evitar que el clic se propague a la imagen

                const imageId = this.dataset.imageId;
                const imageContainer = this.parentElement; // Obtener el contenedor de la imagen
                const formData = new FormData();
                formData.append("imageId", imageId);

                if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
                    fetch(uploadUrl, {
                            method: 'POST',
                            // body: JSON.stringify({
                            //     imageId: imageId
                            // }),
                            body: formData
                        })
                        .then(response => {
                            console.log("Deleter: Respuesta recibida, estado:", response.status);
                            if (!response.ok) {
                                console.error("Deleter: Error en respuesta HTTP:", response.status);
                                return response.text().then(text => {
                                    throw new Error(`Error HTTP ${response.status}: ${text}`);
                                });
                            }
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else if (contentType && contentType.includes('text/html')) {
                                return response.text();
                            } else if (contentType && contentType.includes('text/plain')) {
                                return response.text();
                            } else {
                                return response.text();
                            }
                        })
                        .then(data => {
                            console.log('Se elimino el archivo')
                            formResponsesDiv.innerHTML = '';
                            if (typeof data === 'object' && data !== null) {
                                console.log("UIController: Respuesta JSON del servidor:", data);
                                if (data.status === "success") {
                                    const alert = BootstrapAlertFactory.createAlert({
                                        message: data.message,
                                        type: 'success',
                                        dismissible: true,
                                        icon: "far fa-check-circle",
                                    });
                                    formResponsesDiv.appendChild(alert.generateAlert());
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                } else if (data.status === "fail") {
                                    const alert = BootstrapAlertFactory.createAlert({
                                        message: data.message,
                                        type: 'danger',
                                        dismissible: true,
                                        icon: "far fa-times-circle",
                                    });
                                    formResponsesDiv.appendChild(alert.generateAlert());
                                } else if (data.status === "warning") {
                                    const alert = BootstrapAlertFactory.createAlert({
                                        message: data.message,
                                        type: 'warning',
                                        dismissible: true,
                                        icon: "fas fa-exclamation-triangle",
                                    });
                                    formResponsesDiv.appendChild(alert.generateAlert());
                                }
                            } else if (typeof data === 'string') {
                                formResponsesDiv.innerHTML = data;
                                const alert = BootstrapAlertFactory.createAlert({
                                    message: data || 'Salida modo texto',
                                    type: 'warning',
                                    dismissible: true,
                                    icon: "fas fa-exclamation-triangle",
                                });
                                formResponsesDiv.appendChild(alert.generateAlert());
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            const alert = BootstrapAlertFactory.createAlert({
                                message: error || 'Ocurrió un error en la petición.',
                                type: 'danger',
                                dismissible: true,
                                icon: "far fa-times-circle",
                            });
                            formResponsesDiv.appendChild(alert.generateAlert());
                        });
                }
            });
        });
    });
</script>