<style>
    :root {
        --primary-color: #3b82f6;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --light-bg: #f8fafc;
        --card-bg: #ffffff;
        --hover-bg: #f1f5f9;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.07);
        --border-radius: 10px;
        --transition: all 0.3s ease;
    }

    .header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .header h1 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .project-badge {
        background-color: var(--primary-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-block;
        box-shadow: var(--shadow-sm);
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        height: 100%;
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }

    .card-header-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .card-header-success {
        background-color: var(--success-color);
        color: white;
    }

    .card-area {
        min-height: 400px;
        padding: 1.5rem;
        border-bottom-left-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
        transition: var(--transition);
    }

    .drop-target {
        background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='%23CCCCCC' stroke-width='2' stroke-dasharray='6%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        background-color: rgba(16, 185, 129, 0.05);
    }

    .drop-target.dragover {
        background-color: rgba(16, 185, 129, 0.1);
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #94a3b8;
        text-align: center;
        padding: 2rem;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .service-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        box-shadow: var(--shadow-sm);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: var(--transition);
    }

    .service-card:hover {
        background-color: var(--hover-bg);
        transform: translateY(-2px);
    }

    .service-card.preloaded {
        border-left: 3px solid var(--primary-color);
    }

    .service-card.dragging {
        opacity: 0.5;
        border: 2px dashed var(--primary-color);
    }

    .draggable {
        cursor: grab;
        user-select: none;
    }

    .draggable:active {
        cursor: grabbing;
    }

    .service-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .service-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 1.2rem;
    }

    .preloaded .service-icon {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary-color);
    }

    .service-name {
        font-weight: 500;
    }

    .service-meta {
        font-size: 0.8rem;
        color: #64748b;
    }

    .price-input-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .price-input {
        width: 120px;
        padding: 4px 8px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        text-align: right;
    }

    .drag-icon {
        color: #cbd5e1;
        margin-right: 0.5rem;
    }

    .preloaded-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary-color);
        border-radius: 4px;
        margin-left: 0.5rem;
    }

    .locked-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        background-color: #fef3c7;
        color: #92400e;
        border-radius: 4px;
        margin-left: 0.5rem;
    }

    .action-btn {
        border: none;
        background: none;
        color: #64748b;
        transition: var(--transition);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background-color: #f1f5f9;
        color: #334155;
    }

    .remove-btn:hover {
        background-color: #fee2e2;
        color: var(--danger-color);
    }

    .save-btn {
        background-color: white;
        color: var(--success-color);
        border: 1px solid var(--success-color);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .save-btn:hover {
        background-color: var(--success-color);
        color: white;
    }

    .save-btn i {
        margin-right: 5px;
    }

    .counter-badge {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 50px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        margin-left: 0.75rem;
    }

    .fade-in {
        animation: fadeIn 0.4s ease;
    }

    .fade-out {
        animation: fadeOut 0.4s ease forwards;
    }

    .shake {
        animation: shake 0.5s ease-in-out;
    }

    .flash-success {
        animation: flashSuccess 1s ease;
    }

    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        opacity: 0;
        min-width: 250px;
        margin-bottom: 10px;
        background-color: white;
        color: #333;
        border-radius: 8px;
        padding: 12px 15px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        transform: translateX(50px);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .toast.success {
        border-left: 4px solid var(--success-color);
    }

    .toast.error {
        border-left: 4px solid var(--danger-color);
    }

    .toast-icon {
        font-size: 1.2rem;
        margin-right: 10px;
    }

    .toast.success .toast-icon {
        color: var(--success-color);
    }

    .toast.error .toast-icon {
        color: var(--danger-color);
    }

    .toast-content {
        flex: 1;
    }

    .loader {
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary-color);
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: inline-block;
        margin-right: 8px;
        vertical-align: middle;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(10px);
        }
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        50% {
            transform: translateX(5px);
        }

        75% {
            transform: translateX(-5px);
        }
    }

    @keyframes flashSuccess {

        0%,
        100% {
            background-color: var(--card-bg);
        }

        50% {
            background-color: rgba(16, 185, 129, 0.1);
        }
    }

    /* Tooltip styles */
    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 0.8rem;
        pointer-events: none;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .row>div+div {
            margin-top: 2rem;
        }
    }
</style>
<div id="source-event" data-source-id="<?= $eventoId ?>"></div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"><?= $title ?></h4>
        <p class="mb-0"><?= $subtitle ?></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <div class="d-flex gap-4">
        </div>
    </div>
</div>
<div class="app-container">
    <div id="formResponses"></div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <i class="fas fa-tools me-2"></i> Servicios Disponibles
                        <span id="available-counter" class="counter-badge">3</span>
                    </div>
                    <div class="tooltip">
                        <button class="action-btn">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <span class="tooltiptext">Arrastra los servicios al panel derecho para agregarlos al proyecto</span>
                    </div>
                </div>
                <div id="servicios-disponibles" class="card-area">
                    <div class="service-card draggable" draggable="true" data-id="1">
                        <div class="service-info">
                            <div class="service-icon"><i class="fas fa-wrench"></i></div>
                            <div>
                                <div class="service-name">Servicio de Mantenimiento</div>
                                <div class="service-meta">Precio sugerido: <span data-price="0">$0</span></div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-grip-lines drag-icon"></i>
                        </div>
                    </div>
                    <div class="service-card draggable" draggable="true" data-id="2">
                        <div class="service-info">
                            <div class="service-icon"><i class="fas fa-wrench"></i></div>
                            <div>
                                <div class="service-name">Servicio 2</div>
                                <div class="service-meta">Precio sugerido: <span data-price="0">$0</span></div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-grip-lines drag-icon"></i>
                        </div>
                    </div>

                    <?= $serviciosDisponibles ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <i class="fas fa-clipboard-check me-2"></i> Servicios del Proyecto
                        <span id="assigned-counter" class="counter-badge">0</span>
                    </div>
                    <button id="guardarBtn" class="btn btn-success">
                        <i class="fas fa-save mx-2"></i> Guardar
                    </button>
                </div>
                <div id="servicios-asignados" class="card-area drop-target">
                    <!-- <div class="service-card" data-id="2">
                        <div class="service-info">
                            <div class="service-icon"><i class="fas fa-paint-roller"></i></div>
                            <div>
                                <div class="service-name">Servicio de Pintura</div>
                                <div class="service-meta">
                                    <div class="price-input-group">
                                        $ <input type="number" class="price-input" value="1250.00" step="0.01" min="0" data-id="2">
                                        <button class="action-btn save-price-btn"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="action-btn remove-btn"><i class="fas fa-times"></i></button>
                        </div>
                    </div> -->

                    <?= $serviciosSeleccionados ?>

                    <div id="empty-state" class="empty-state" style="display: none;">
                        <i class="fas fa-tools"></i>
                        <h5>Sin servicios asignados</h5>
                        <p>Arrastra servicios desde el panel izquierdo para agregarlos a este proyecto</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="toast-container"></div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log("Document ready, initializing script...");

        // --- Configuración base ---
        const decoder = new Base64Decoder("trash");
        const base = decoder.decode();
        const urlRemoveService = `${base}/eventos/servicio/delete`;
        const urlSaveService = `${base}/eventos/servicio/save`;
        const urlUpdateServicePrice = `${base}/servicio/update`;

        const availableServicesContainer = document.getElementById('servicios-disponibles');
        const assignedServicesContainer = document.getElementById('servicios-asignados');
        const emptyState = document.getElementById('empty-state');
        const availableCounter = document.getElementById('available-counter');
        const assignedCounter = document.getElementById('assigned-counter');
        const saveBtn = document.getElementById('guardarBtn');
        const toastContainer = document.querySelector('.toast-container');
        const sourceEvent = document.getElementById('source-event');
        const eventId = sourceEvent.dataset.sourceId;

        const myAjax = new ModernAjaxHandler({
            timeout: 10000
        });

        // --- Utilidades ---
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            let iconHtml = '';
            if (type === 'success') iconHtml = '<i class="fas fa-check-circle"></i>';
            else if (type === 'error') iconHtml = '<i class="fas fa-exclamation-circle"></i>';
            else if (type === 'loading') iconHtml = '<span class="loader"></span>';

            toast.innerHTML = `
      <div class="toast-icon">${iconHtml}</div>
      <div class="toast-content">${message}</div>
    `;
            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);

            if (type !== 'loading') {
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }
            return toast;
        }

        function updateCounters() {
            const assignedCount = assignedServicesContainer.querySelectorAll('.service-card').length;
            assignedCounter.textContent = assignedCount;
            const availableCount = availableServicesContainer.querySelectorAll('.service-card').length;
            availableCounter.textContent = availableCount;
            emptyState.style.display = assignedCount > 0 ? 'none' : 'flex';
        }

        // --- Drag & Drop base ---
        function initializeDraggable(element) {
            element.classList.add('draggable');
            element.setAttribute("draggable", "true");
            element.addEventListener('dragstart', e => {
                e.dataTransfer.setData('text/plain', element.dataset.id);
                e.dataTransfer.effectAllowed = "move";
                setTimeout(() => element.classList.add('dragging'), 0);
            });
            element.addEventListener('dragend', () => {
                element.classList.remove('dragging');
            });
        }

        // Limpieza global por seguridad
        document.addEventListener('dragend', () => {
            document.querySelectorAll('.service-card.dragging')
                .forEach(el => el.classList.remove('dragging'));
        });

        // Inicializar arrastrables existentes
        document.querySelectorAll('.service-card.draggable').forEach(initializeDraggable);

        // --- Helpers de transformación de tarjetas ---
        // Convierte una tarjeta asignada en disponible (corrige el error de la "X")
        function toAvailableCard(fromCard) {
            const card = fromCard.cloneNode(true);

            // Quitar clases/atributos de asignado
            card.classList.remove('fade-out', 'shake', 'dragging', 'preloaded');
            card.removeAttribute('data-preloaded');

            // Restaurar área de acciones: reemplazar botón "X" por ícono de arrastre
            const actionsContainer =
                card.querySelector('.remove-btn')?.parentElement ||
                card.querySelector('.preloaded-remove-btn')?.parentElement ||
                card.querySelector('.drag-icon')?.parentElement;

            if (actionsContainer) {
                actionsContainer.innerHTML = '<i class="fas fa-grip-lines drag-icon"></i>';
            }

            // Restaurar metadata a texto de costo
            const serviceMeta = card.querySelector('.service-meta');
            if (serviceMeta) {
                const priceVal =
                    fromCard.querySelector('.price-input')?.value ||
                    serviceMeta.querySelector('[data-price]')?.dataset.price ||
                    '0.00';
                serviceMeta.innerHTML = `Costo: <span data-price="${priceVal}">$${priceVal}</span>`;
            }

            // Hacerla arrastrable nuevamente
            card.setAttribute('draggable', 'true');
            initializeDraggable(card);
            return card;
        }

        // Crea tarjeta para panel derecho (asignados) con botón X y input de precio
        function createAssignedCard(originalCard) {
            const clonedCard = originalCard.cloneNode(true);
            clonedCard.classList.remove('draggable', 'dragging');
            clonedCard.setAttribute('draggable', 'false');
            clonedCard.classList.add('fade-in');

            // Reemplazar ícono de drag por botón de eliminar dentro de su contenedor
            const actionsContainer = clonedCard.querySelector('.drag-icon')?.parentElement ||
                clonedCard.querySelector('.remove-btn')?.parentElement;

            if (actionsContainer) {
                actionsContainer.innerHTML = '';
                const removeBtn = document.createElement('button');
                removeBtn.className = 'action-btn remove-btn';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.addEventListener('click', () => removeAssignedService(clonedCard));
                actionsContainer.appendChild(removeBtn);
            }

            // Eliminar badge precargado si existe
            clonedCard.querySelector('.preloaded-badge')?.remove();

            // Reemplazar metadata por input de precio
            const serviceMeta = clonedCard.querySelector('.service-meta');
            if (serviceMeta) {
                const originalPrice = serviceMeta.querySelector('[data-price]')?.dataset.price || '0.00';
                serviceMeta.innerHTML = `
        <div class="price-input-group">
          <input type="number" class="price-input" value="${originalPrice}" step="0.01" min="0" data-id="${clonedCard.dataset.id}">
        </div>
      `;
                //             serviceMeta.innerHTML = `
                //     <div class="price-input-group">
                //       <input type="number" class="price-input" value="${originalPrice}" step="0.01" min="0" data-id="${clonedCard.dataset.id}">
                //       <button class="action-btn save-price-btn"><i class="fas fa-check"></i></button>
                //     </div>
                //   `;
                // const savePriceBtn = serviceMeta.querySelector('.save-price-btn');
                // savePriceBtn.addEventListener('click', () => {
                //     const priceInput = serviceMeta.querySelector('.price-input');
                //     updateServicePrice(priceInput.dataset.id, priceInput.value, serviceMeta);
                // });
            }

            return clonedCard;
        }

        // --- Eventos contenedor derecho ---
        assignedServicesContainer.addEventListener('dragover', e => {
            e.preventDefault();
            assignedServicesContainer.classList.add('dragover');
        });

        assignedServicesContainer.addEventListener('dragleave', () => {
            assignedServicesContainer.classList.remove('dragover');
        });

        assignedServicesContainer.addEventListener('drop', e => {
            e.preventDefault();
            assignedServicesContainer.classList.remove('dragover');

            const id = e.dataTransfer.getData('text/plain');
            const draggedElement = document.querySelector(`.service-card[data-id="${id}"]`);

            if (draggedElement) draggedElement.classList.remove('dragging'); // limpiar

            if (draggedElement && !assignedServicesContainer.querySelector(`.service-card[data-id="${id}"]`)) {
                const assignedCard = createAssignedCard(draggedElement);
                assignedServicesContainer.appendChild(assignedCard);
                draggedElement.remove();
                updateCounters();
            } else if (draggedElement) {
                const duplicate = assignedServicesContainer.querySelector(`.service-card[data-id="${id}"]`);
                if (duplicate) {
                    duplicate.classList.add('shake');
                    setTimeout(() => duplicate.classList.remove('shake'), 500);
                }
            }
        });

        // --- Eliminar servicio asignado (vuelve a la izquierda SIN la "X") ---
        function removeAssignedService(serviceCard) {
            serviceCard.classList.add('shake');
            setTimeout(() => {
                serviceCard.classList.add('fade-out');
                setTimeout(() => {
                    const availableCard = toAvailableCard(serviceCard);
                    availableServicesContainer.appendChild(availableCard);
                    serviceCard.remove();
                    updateCounters();
                }, 400);
            }, 300);
        }

        // --- AJAX: actualizar precio ---
        function updateServicePrice(serviceId, newPrice, serviceMetaElement) {
            if (isNaN(parseFloat(newPrice)) || parseFloat(newPrice) < 0) {
                showToast('El precio debe ser un número positivo.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('service_id', serviceId);
            formData.append('price', newPrice);
            formData.append('event_id', eventId);

            const loadingToast = showToast('Actualizando precio...', 'loading');

            myAjax.post(urlUpdateServicePrice, formData)
                .then(response => {
                    loadingToast.classList.remove('show');
                    setTimeout(() => loadingToast.remove(), 300);
                    if (response.status === 'success' || response.success) {
                        showToast(response.message, 'success');
                        serviceMetaElement.classList.add('flash-success');
                        setTimeout(() => serviceMetaElement.classList.remove('flash-success'), 1000);
                    } else {
                        showToast(response.message || 'Error al actualizar el precio.', 'error');
                    }
                })
                .catch(() => {
                    loadingToast.classList.remove('show');
                    setTimeout(() => loadingToast.remove(), 300);
                    showToast('Ocurrió un error al actualizar el precio.', 'error');
                });
        }

        // --- Servicios precargados (los del panel derecho que vienen del servidor) ---
        document.querySelectorAll('.preloaded-remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const serviceCard = this.closest('.service-card');
                const serviceId = serviceCard.dataset.id;
                serviceCard.classList.add('shake');

                const formData = new FormData();
                formData.append('service_id', serviceId);
                formData.append('event_id', eventId);

                const loadingToast = showToast('Eliminando servicio...', 'loading');

                // myAjax.post(urlRemoveService, formData)
                myAjax.post(urlRemoveService, formData, {
                        headers: {
                            // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
                        }
                    })
                    .then(response => {
                        loadingToast.classList.remove('show');
                        setTimeout(() => loadingToast.remove(), 300);
                        if (response.status === 'success' || response.success) {
                            serviceCard.classList.add('fade-out');
                            setTimeout(() => {
                                const availableCard = toAvailableCard(serviceCard);
                                availableServicesContainer.appendChild(availableCard);
                                serviceCard.remove();
                                updateCounters();
                                showToast(response.message, 'success');
                            }, 400);
                        } else {
                            showToast(response.message || 'Error al eliminar el servicio.', 'error');
                            serviceCard.classList.remove('shake');
                        }
                    })
                    .catch(() => {
                        loadingToast.classList.remove('show');
                        setTimeout(() => loadingToast.remove(), 300);
                        showToast('Ocurrió un error en la solicitud.', 'error');
                        serviceCard.classList.remove('shake');
                    });
            });
        });

        // --- Guardar asignaciones ---
        // saveBtn.addEventListener('click', () => {
        //     const assignedServices = assignedServicesContainer.querySelectorAll('.service-card');
        //     if (assignedServices.length === 0) {
        //         showToast('No hay servicios asignados al proyecto.', 'error');
        //         return;
        //     }

        //     const servicesToAdd = [];
        //     assignedServices.forEach(el => {
        //         const serviceId = el.dataset.id;
        //         if (!el.classList.contains('preloaded') && el.getAttribute('data-preloaded') === null) {
        //             servicesToAdd.push(serviceId);
        //         }
        //         el.classList.add('flash-success');
        //         setTimeout(() => el.classList.remove('flash-success'), 1000);
        //     });

        //     const formData = new FormData();
        //     formData.append('services_ids', JSON.stringify(servicesToAdd));
        //     formData.append('event_id', eventId);

        //     const loadingToast = showToast('Guardando asignaciones...', 'loading');

        //     // myAjax.post(urlSaveService, formData)
        //     myAjax.post(urlSaveService, formData, {
        //             headers: {
        //                 // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
        //             }
        //         })
        //         .then(response => {
        //             loadingToast.classList.remove('show');
        //             setTimeout(() => loadingToast.remove(), 300);
        //             console.log(response);
        //             // if (response.status === 'success' || response.success) {
        //             //     showToast(response.message, 'success');
        //             //     setTimeout(() => window.location.reload(), 1000);
        //             // } else {
        //             //     showToast(response.message || 'Error al guardar las asignaciones.', 'error');
        //             // }
        //         })
        //         .catch(() => {
        //             loadingToast.classList.remove('show');
        //             setTimeout(() => loadingToast.remove(), 300);
        //             showToast('Ocurrió un error en la solicitud.', 'error');
        //         });
        // });
        // --- Guardar asignaciones ---
        saveBtn.addEventListener('click', () => {
            const assignedServices = assignedServicesContainer.querySelectorAll('.service-card');
            if (assignedServices.length === 0) {
                showToast('No hay servicios asignados al proyecto.', 'error');
                return;
            }

            const formData = new FormData();

            assignedServices.forEach(el => {
                const serviceId = el.dataset.id;
                const priceInput = el.querySelector('.price-input');
                const price = priceInput ? priceInput.value : 0;

                // Enviar como vectores
                formData.append('services_ids[]', serviceId);
                formData.append('services_prices[]', price);

                // marcar animación visual
                el.classList.add('flash-success');
                setTimeout(() => el.classList.remove('flash-success'), 1000);
            });

            formData.append('event_id', eventId);

            const loadingToast = showToast('Guardando asignaciones...', 'loading');

            myAjax.post(urlSaveService, formData, {
                    headers: {
                        // No es necesario configurar 'Content-Type' para FormData
                    }
                })
                .then(response => {
                    loadingToast.classList.remove('show');
                    setTimeout(() => loadingToast.remove(), 300);
                    console.log(response);
                    // if (response.status === 'success' || response.success) {
                    //     showToast(response.message, 'success');
                    //     setTimeout(() => window.location.reload(), 1000);
                    // } else {
                    //     showToast(response.message || 'Error al guardar las asignaciones.', 'error');
                    // }
                })
                .catch(() => {
                    loadingToast.classList.remove('show');
                    setTimeout(() => loadingToast.remove(), 300);
                    showToast('Ocurrió un error en la solicitud.', 'error');
                });
        });

        // --- Inicialización ---
        updateCounters();

        // Cambios de precio para inputs presentes al cargar (si hubiese)
        document.querySelectorAll('.price-input').forEach(input => {
            input.addEventListener('change', e => {
                updateServicePrice(e.target.dataset.id, e.target.value, e.target.closest('.service-meta'));
            });
        });
    });
</script>