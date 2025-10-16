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

    .vendor-card {
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

    .vendor-card:hover {
        background-color: var(--hover-bg);
        transform: translateY(-2px);
    }

    .vendor-card.preloaded {
        border-left: 3px solid var(--primary-color);
    }

    .draggable {
        cursor: grab;
        user-select: none;
    }

    .draggable:active {
        cursor: grabbing;
    }

    .vendor-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .vendor-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #64748b;
    }

    .preloaded .vendor-avatar {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary-color);
    }

    .vendor-name {
        font-weight: 500;
    }

    .vendor-meta {
        font-size: 0.8rem;
        color: #64748b;
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
                        <i class="fas fa-users me-2"></i> Vendedores Disponibles
                        <span id="available-counter" class="counter-badge">3</span>
                    </div>
                    <div class="tooltip">
                        <button class="action-btn">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <span class="tooltiptext">Arrastra los vendedores hacia el panel derecho para asignarlos</span>
                    </div>
                </div>
                <div id="vendedores" class="card-area">
                    <?= $vendedoresDisponibles ?>
                    <!-- <div class="vendor-card draggable" draggable="true" data-id="1">
                        <div class="vendor-info">
                            <div class="vendor-avatar">JP</div>
                            <div>
                                <div class="vendor-name">Juan Pérez</div>
                                <div class="vendor-meta">Ventas: <span class="text-success">15 proyectos</span></div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-grip-lines drag-icon"></i>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <i class="fas fa-clipboard-check me-2"></i> Asignados al Proyecto
                        <span id="assigned-counter" class="counter-badge">0</span>
                    </div>
                    <button id="guardarBtn" class="btn btn-success">
                        <i class="fas fa-save mx-2"></i> Guardar
                    </button>
                </div>
                <div id="asignados" class="card-area drop-target">
                    <?= $vendedoresSeleccionados ?>
                    <div id="empty-state" class="empty-state" style="display: none;">
                        <i class="fas fa-people-arrows"></i>
                        <h5>Sin vendedores asignados</h5>
                        <p>Arrastra vendedores desde el panel izquierdo para asignarlos a este proyecto</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="toast-container"></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const decoder = new Base64Decoder("trash");
        let urlRemoveVendor = decoder.decode() + '/eventos/vendedor/delete';
        let urlSaveVendors = decoder.decode() + '/eventos/vendedores/save';

        const draggables = document.querySelectorAll('.draggable');
        const dropTarget = document.getElementById('asignados');
        const emptyState = document.getElementById('empty-state');
        const availableCounter = document.getElementById('available-counter');
        const assignedCounter = document.getElementById('assigned-counter');
        const saveBtn = document.getElementById('guardarBtn');
        const toastContainer = document.querySelector('.toast-container');
        const sourceEvent = document.getElementById('source-event');
        const eventId = sourceEvent.dataset.sourceId;
        const availableVendorsContainer = document.getElementById('vendedores'); // Get the container for available vendors

        const myAjax = new ModernAjaxHandler({
            timeout: 10000, // Timeout global de 10 segundos (opcional)
        });

        setupPreloadedVendors();

        function setupPreloadedVendors() {
            const preloadedRemoveBtns = document.querySelectorAll('.preloaded-remove-btn');

            preloadedRemoveBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const vendorId = this.getAttribute('data-id');
                    removePreloadedVendor(vendorId, this.closest('.vendor-card'));
                });
            });
            updateCounters();
        }
        // Función para eliminar un vendedor precargado vía AJAX
        function removePreloadedVendor(vendorId, vendorCard) {
            // Añadir efecto visual antes de eliminar
            vendorCard.classList.add('shake');
            // Crear datos para la petición AJAX
            const formData = new FormData();
            // formData.append('action', 'remove_preloaded_vendor');
            formData.append('user_id', vendorId);
            formData.append('event_id', eventId); // O el ID real del proyecto
            // Mostrar toast de carga
            const loadingToast = showToast('Eliminando vendedor de la selección...', 'loading');
            myAjax.post(urlRemoveVendor, formData, {
                    headers: {
                        // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
                    }
                }).then(response => {
                    let responseDiv = document.getElementById("formResponses");
                    setTimeout(() => {
                        // console.log('Respuesta:', response);
                        // console.log(typeof response);
                        if (loadingToast) {
                            loadingToast.classList.remove('show');
                            setTimeout(() => loadingToast.remove(), 300);
                        }
                        if (typeof response === 'object') {
                            if (response.status === 'success' || response.success) {
                                setTimeout(() => {
                                    vendorCard.classList.add('fade-out');
                                    setTimeout(() => {
                                        const clonedVendor = vendorCard.cloneNode(true);
                                        clonedVendor.classList.remove('preloaded', 'fade-out', 'shake');
                                        clonedVendor.removeAttribute('data-preloaded');
                                        const removeButton = clonedVendor.querySelector('.remove-btn');
                                        if (removeButton) {
                                            const actionArea = document.createElement('div'); // Create the div
                                            const dragIcon = document.createElement('i');
                                            dragIcon.className = 'fas fa-grip-lines drag-icon';
                                            actionArea.appendChild(dragIcon); // Append the icon to the div
                                            removeButton.parentNode.replaceChild(actionArea, removeButton);
                                        }
                                        const preloadedBadge = clonedVendor.querySelector('.preloaded-badge');
                                        if (preloadedBadge) {
                                            preloadedBadge.remove();
                                        }
                                        clonedVendor.classList.add('draggable');
                                        clonedVendor.draggable = true;
                                        initializeDraggable(clonedVendor);
                                        availableVendorsContainer.appendChild(clonedVendor);
                                        vendorCard.remove();
                                        updateCounters();
                                        showToast(response.message, 'success');
                                    }, 400);
                                }, 100);
                            } else {
                                showToast(response.message, 'error');
                            }
                        } else {
                            responseDiv.innerHTML = response;
                            showToast('No se pudo completar la acción', 'error');
                        }
                    }, 1000);
                })
                .catch(error => {
                    showToast('Ocurrio un error.', 'dangers');
                    // console.error('Error al subir el archivo:', error);
                });
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            let iconClass = '';
            if (type === 'success') iconClass = 'fas fa-check-circle';
            else if (type === 'error') iconClass = 'fas fa-exclamation-circle';
            else if (type === 'loading') iconClass = '';

            toast.innerHTML = `
    <div class="toast-icon">
      ${type === 'loading'
        ? '<span class="loader"></span>'
        : `<i class="${iconClass}"></i>`}
    </div>
    <div class="toast-content">${message}</div>
  `;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            if (type !== 'loading') {
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 3000); // Changed to 5000 milliseconds (5 seconds)
                }, 2000);
            }
            return toast;
        }

        function updateCounters() {
            const assignedCount = dropTarget.querySelectorAll('.vendor-card').length;
            assignedCounter.textContent = assignedCount;
            const availableCount = availableVendorsContainer.querySelectorAll('.vendor-card').length;
            availableCounter.textContent = availableCount;
            if (assignedCount > 0) {
                emptyState.style.display = 'none';
            } else {
                emptyState.style.display = 'flex';
            }
        }
        draggables.forEach(el => {
            el.addEventListener('dragstart', e => {
                const vendorId = el.dataset.id;
                e.dataTransfer.setData('text/plain', vendorId);
                e.dataTransfer.effectAllowed = "move";
                setTimeout(() => {
                    el.classList.add('dragging');
                }, 0);
            });
            el.addEventListener('dragend', () => {
                el.classList.remove('dragging');
            });
        });
        dropTarget.addEventListener('dragover', e => {
            e.preventDefault();
            dropTarget.classList.add('dragover');
        });
        dropTarget.addEventListener('dragleave', () => {
            dropTarget.classList.remove('dragover');
        });
        dropTarget.addEventListener('drop', e => {
            e.preventDefault();
            dropTarget.classList.remove('dragover');
            const id = e.dataTransfer.getData('text/plain');
            const dragged = document.querySelector(`[data-id="${id}"]`);
            if (dragged) {
                // console.log('Drop - Dragged Element Inner HTML:', dragged.innerHTML);
            }
            const assigned = dragged ? dragged.cloneNode(true) : null;
            if (assigned) {
                // console.log('Drop - Cloned Element Inner HTML:', assigned.innerHTML);
            }
            if (assigned && !dropTarget.querySelector(`[data-id="${id}"]`)) {
                assigned.classList.remove('draggable');
                assigned.draggable = false;
                assigned.classList.add('fade-in');
                const actionArea = assigned.querySelector('.fa-grip-lines') ? assigned.querySelector('.fa-grip-lines').parentNode : null;
                if (actionArea) {
                    actionArea.innerHTML = '';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'action-btn remove-btn';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.onclick = function() {
                        assigned.classList.add('shake');
                        setTimeout(() => {
                            assigned.remove();
                            // Verificar si el vendedor no es precargado para reinsertarlo
                            if (!assigned.hasAttribute('data-preloaded')) {
                                const originalAvailable = dragged.cloneNode(true);
                                availableVendorsContainer.appendChild(originalAvailable);
                                initializeDraggable(originalAvailable); // Make the new element draggable
                            }
                            updateCounters();
                        }, 400);
                    };
                    actionArea.appendChild(removeBtn);
                }
                dropTarget.appendChild(assigned);
                if (dragged) {
                    dragged.remove();
                }
                updateCounters();
            } else if (assigned) {
                const duplicate = dropTarget.querySelector(`[data-id="${id}"]`);
                if (duplicate) {
                    duplicate.classList.add('shake');
                    setTimeout(() => {
                        duplicate.classList.remove('shake');
                    }, 500);
                }
            }
        });
        saveBtn.addEventListener('click', () => {
            const asignados = dropTarget.querySelectorAll('[data-id]');
            if (asignados.length === 0) {
                showToast('No hay vendedores asignados al proyecto.', 'error');
                return;
            }
            const formData = new FormData();
            const vendedoresIds = [];
            const vendedoresPreloaded = [];
            asignados.forEach((el) => {
                const vendorId = el.dataset.id;
                const isPreloaded = el.hasAttribute('data-preloaded');
                vendedoresIds.push(vendorId);
                if (!isPreloaded) {
                    formData.append(`agregar_${vendedoresIds.length - 1}`, vendorId);
                }
                if (isPreloaded) {
                    vendedoresPreloaded.push(vendorId);
                    // formData.append(`existente_${vendedoresPreloaded.length - 1}`, vendorId);
                }
                el.classList.add('flash-success');
                setTimeout(() => {
                    el.classList.remove('flash-success');
                }, 1000);
            });
            formData.append('event_id', eventId);
            const loadingToast = showToast('Guardando asignaciones...', 'loading');
            myAjax.post(urlSaveVendors, formData, {
                    headers: {
                        // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
                    }
                })
                .then(response => {
                    let responseDiv = document.getElementById("formResponses");
                    setTimeout(() => {
                        if (loadingToast) {
                            loadingToast.classList.remove('show');
                            setTimeout(() => loadingToast.remove(), 300);
                        }
                        if (typeof response === 'object') {
                            console.log(response);
                            if (response.status === 'success' || response.success) {
                                const alert = BootstrapAlertFactory.createAlert({
                                    message: response.message,
                                    type: "success",
                                    dismissible: true,
                                    icon: "far fa-check-circle",
                                });
                                responseDiv.appendChild(alert.generateAlert());
                                showToast(response.message, 'success');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                showToast(response.message, 'error');
                            }
                        } else {
                            responseDiv.innerHTML = response;
                        }
                    }, 1000);
                })
                .catch(error => {
                    showToast('Ocurrio un error.', 'dangers');
                    console.error('Error al subir el archivo:', error);
                });

        });

        function initializeDraggable(element) {
            element.addEventListener('dragstart', e => {
                e.dataTransfer.setData('text/plain', element.dataset.id);
                e.dataTransfer.effectAllowed = "move";
                setTimeout(() => {
                    element.classList.add('dragging');
                }, 0);
            });
            element.addEventListener('dragend', () => {
                element.classList.remove('dragging');
            });
        }
    });
</script>