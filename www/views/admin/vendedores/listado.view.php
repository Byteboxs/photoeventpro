<!-- <h1>En construcción <small>/views/admin/vendedores/listado.view.php</small> </h1> -->
<style>
    .table-container {
        position: relative;
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
        position: relative;
        scrollbar-width: thin;
        scroll-behavior: smooth;
        /* Scroll suave por CSS */
    }

    .table-scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(0, 123, 255, 0.7);
        color: white;
        border: none;
        font-size: 20px;
        z-index: 100;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);

        /* Propiedades para la animación */
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease, opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
    }

    .table-scroll-btn.visible {
        opacity: 1;
        visibility: visible;
    }

    .table-scroll-btn:hover {
        background-color: rgba(0, 123, 255, 0.9);
        transform: translateY(-50%) scale(1.1);
    }

    .table-scroll-btn:active {
        transform: translateY(-50%) scale(0.95);
    }

    .table-scroll-btn-left {
        left: 5px;
        transform: translateY(-50%) translateX(-10px);
    }

    .table-scroll-btn-left.visible {
        transform: translateY(-50%) translateX(0);
    }

    .table-scroll-btn-right {
        right: 5px;
        transform: translateY(-50%) translateX(10px);
    }

    .table-scroll-btn-right.visible {
        transform: translateY(-50%) translateX(0);
    }

    /* Para tablas que no necesitan scroll */
    .no-scroll .table-scroll-btn {
        opacity: 0;
        visibility: hidden;
    }
</style>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"><?= $title ?></h4>
        <p class="mb-0"><?= $subtitle ?></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <?= $linkCreateVendedor ?>
    </div>
</div>
<div class="row g-6 mb-6">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="row needs-validation" id="myForm" method="get" action="" novalidate>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" id="basic-addon-search31"><i class="icon-base bx bx-search"></i></span>
                        <input type="text" name="value" id="value" class="form-control flex-grow-1" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon-search31">
                        <select
                            class="form-select flex-shrink-1"
                            style="max-width: 190px; min-width: 100px;"
                            id="criteria"
                            name="criteria"
                            aria-label="Example select with button addon"
                            data-bs-toggle="tooltip" data-bs-title="Seleccione un criterio">
                            <option value="apellido" selected="">Nombre</option>
                            <option value="documento">Número Documento</option>
                        </select>
                        <button class="btn btn-outline-primary" type="submit" data-bs-toggle="tooltip" data-bs-title="Realizar busqueda">
                            <i class='bx bx-search-alt-2'></i>
                        </button>
                        <a class="btn btn-outline-primary" href="<?= $selfLink ?>" data-bs-toggle="tooltip" data-bs-title="Limpiar resultados">
                            <i class='bx bx-brush'></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row g-6 mb-6">
    <div class="col-12">
        <div id="marcadorTabla" class="card overflow-hidden">
            <h5 class="card-header"><i class="fas fa-users"></i> Mis vendedores</h5>
            <div class="table-container">
                <div class="table-responsive">
                    <?= $table->render(); ?>
                </div>
            </div>
            <button class="table-scroll-btn table-scroll-btn-left">&lt;</button>
            <button class="table-scroll-btn table-scroll-btn-right">&gt;</button>
            <div class="card-footer text-muted">
                <div class="row">
                    <div class="col-4">
                        <!-- <div class="input-group">
                            <select class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                                <option selected="">Seleccione</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <button class="btn btn-outline-primary" type="button">Acción en lote</button>
                        </div> -->
                    </div>
                    <div class="col-8">
                        <?= $paginator->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para inicializar los botones de desplazamiento en todas las tablas
        function initTableScrollButtons() {
            // Seleccionar todos los contenedores de tablas
            const tableContainers = document.querySelectorAll('.table-container');

            tableContainers.forEach(container => {
                const tableWrapper = container.querySelector('.table-responsive');
                const leftBtn = container.querySelector('.table-scroll-btn-left');
                const rightBtn = container.querySelector('.table-scroll-btn-right');

                // Si no existen los botones, los creamos dinámicamente
                if (!leftBtn || !rightBtn) {
                    // Crear botones si no existen
                    const newLeftBtn = document.createElement('button');
                    newLeftBtn.className = 'table-scroll-btn table-scroll-btn-left';
                    newLeftBtn.innerHTML = '&lt;';

                    const newRightBtn = document.createElement('button');
                    newRightBtn.className = 'table-scroll-btn table-scroll-btn-right';
                    newRightBtn.innerHTML = '&gt;';

                    container.appendChild(newLeftBtn);
                    container.appendChild(newRightBtn);

                    // Actualizar referencias
                    leftBtnRef = newLeftBtn;
                    rightBtnRef = newRightBtn;
                } else {
                    leftBtnRef = leftBtn;
                    rightBtnRef = rightBtn;
                }

                // Comprobar si hay scroll horizontal
                checkScroll(tableWrapper, leftBtnRef, rightBtnRef);

                // Función de animación suave para el scroll
                function smoothScroll(element, target, duration) {
                    const start = element.scrollLeft;
                    const change = target - start;
                    let startTime = null;

                    function animation(currentTime) {
                        if (startTime === null) startTime = currentTime;
                        const timeElapsed = currentTime - startTime;
                        const progress = Math.min(timeElapsed / duration, 1);
                        // Función de aceleración/desaceleración suave
                        const ease = easeInOutQuad(progress);

                        element.scrollLeft = start + change * ease;

                        if (timeElapsed < duration) {
                            requestAnimationFrame(animation);
                        } else {
                            // Actualizar visibilidad de botones después de la animación
                            updateButtonVisibility(element, leftBtnRef, rightBtnRef);
                        }
                    }

                    requestAnimationFrame(animation);
                }

                // Función de aceleración/desaceleración para la animación
                function easeInOutQuad(t) {
                    return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
                }

                // Listeners para los botones con animación
                leftBtnRef.addEventListener('click', function() {
                    const targetScroll = tableWrapper.scrollLeft - 300;
                    smoothScroll(tableWrapper, targetScroll, 400); // 400ms de duración
                });

                rightBtnRef.addEventListener('click', function() {
                    const targetScroll = tableWrapper.scrollLeft + 300;
                    smoothScroll(tableWrapper, targetScroll, 400); // 400ms de duración
                });

                // Actualizar visibilidad de botones al hacer scroll
                tableWrapper.addEventListener('scroll', function() {
                    updateButtonVisibility(tableWrapper, leftBtnRef, rightBtnRef);
                });

                // Actualizar al cambiar el tamaño de la ventana
                window.addEventListener('resize', function() {
                    checkScroll(tableWrapper, leftBtnRef, rightBtnRef);
                });
            });
        }

        // Función para comprobar si hay scroll horizontal
        function checkScroll(tableWrapper, leftBtn, rightBtn) {
            // Comprobar si el contenido es más ancho que el contenedor
            const hasHorizontalScroll = tableWrapper.scrollWidth > tableWrapper.clientWidth;

            // Añadir o quitar clase no-scroll
            if (hasHorizontalScroll) {
                tableWrapper.parentNode.classList.remove('no-scroll');
                updateButtonVisibility(tableWrapper, leftBtn, rightBtn);
            } else {
                tableWrapper.parentNode.classList.add('no-scroll');
                // Ocultar botones con animación
                leftBtn.classList.remove('visible');
                rightBtn.classList.remove('visible');
            }
        }

        // Actualizar visibilidad de botones según la posición del scroll
        function updateButtonVisibility(tableWrapper, leftBtn, rightBtn) {
            // Mostrar/ocultar botón izquierdo según posición de scroll
            if (tableWrapper.scrollLeft > 10) {
                leftBtn.classList.add('visible');
            } else {
                leftBtn.classList.remove('visible');
            }

            // Mostrar/ocultar botón derecho según si hay más contenido a la derecha
            const maxScrollLeft = tableWrapper.scrollWidth - tableWrapper.clientWidth - 10;
            if (tableWrapper.scrollLeft < maxScrollLeft) {
                rightBtn.classList.add('visible');
            } else {
                rightBtn.classList.remove('visible');
            }
        }

        // Inicializar los botones
        initTableScrollButtons();

        // Función para aplicar a nuevas tablas que se añadan dinámicamente
        function setupNewTable(tableContainer) {
            // Asegurarse de que tiene la estructura correcta
            if (!tableContainer.classList.contains('table-container')) {
                tableContainer.classList.add('table-container');
            }

            // Buscar o crear el div .table-responsive
            let tableWrapper = tableContainer.querySelector('.table-responsive');
            if (!tableWrapper) {
                const table = tableContainer.querySelector('table');
                if (table) {
                    tableWrapper = document.createElement('div');
                    tableWrapper.className = 'table-responsive';
                    tableContainer.appendChild(tableWrapper);
                    tableWrapper.appendChild(table);
                }
            }

            // Crear los botones si no existen
            let leftBtn = tableContainer.querySelector('.table-scroll-btn-left');
            let rightBtn = tableContainer.querySelector('.table-scroll-btn-right');

            if (!leftBtn) {
                leftBtn = document.createElement('button');
                leftBtn.className = 'table-scroll-btn table-scroll-btn-left';
                leftBtn.innerHTML = '&lt;';
                tableContainer.appendChild(leftBtn);
            }

            if (!rightBtn) {
                rightBtn = document.createElement('button');
                rightBtn.className = 'table-scroll-btn table-scroll-btn-right';
                rightBtn.innerHTML = '&gt;';
                tableContainer.appendChild(rightBtn);
            }

            // Aplicar la lógica de scroll con animación
            checkScroll(tableWrapper, leftBtn, rightBtn);

            // Función de animación suave
            function smoothScroll(element, target, duration) {
                const start = element.scrollLeft;
                const change = target - start;
                let startTime = null;

                function animation(currentTime) {
                    if (startTime === null) startTime = currentTime;
                    const timeElapsed = currentTime - startTime;
                    const progress = Math.min(timeElapsed / duration, 1);
                    const ease = easeInOutQuad(progress);

                    element.scrollLeft = start + change * ease;

                    if (timeElapsed < duration) {
                        requestAnimationFrame(animation);
                    } else {
                        updateButtonVisibility(element, leftBtn, rightBtn);
                    }
                }

                requestAnimationFrame(animation);
            }

            // Función de aceleración/desaceleración
            function easeInOutQuad(t) {
                return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
            }

            leftBtn.addEventListener('click', function() {
                const targetScroll = tableWrapper.scrollLeft - 300;
                smoothScroll(tableWrapper, targetScroll, 400);
            });

            rightBtn.addEventListener('click', function() {
                const targetScroll = tableWrapper.scrollLeft + 300;
                smoothScroll(tableWrapper, targetScroll, 400);
            });

            tableWrapper.addEventListener('scroll', function() {
                updateButtonVisibility(tableWrapper, leftBtn, rightBtn);
            });
        }

        // Exponer función para uso externo
        window.setupTableScrollButtons = setupNewTable;
    });
</script>