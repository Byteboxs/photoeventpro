<link rel="stylesheet" href="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/css/possystem.css" />
<nav class="navbar navbar-expand navbar-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-cash-register me-2"></i>
            Punto de Venta
        </a>
        <div class="d-flex align-items-center">
            <!-- <div class="text-light me-3">
                <i class="far fa-user me-1"></i> Admin
            </div> -->
            <div class="">
                <i class="far fa-clock me-1"></i> <span id="current-time">10:30 AM</span>
            </div>
        </div>
    </div>
</nav>
<div id="generalResponses"></div>
<input type="hidden" id="my_event" name="my_event" value="<?= $event_id ?>">
<input type="hidden" id="sales_person_id" name="sales_person_id" value="<?= $sales_person_id ?>">
<div class="pos-container">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-tag"></i> Cliente
                </div>
                <div class="card-body">
                    <div id="customer-selected" class="customer-badge" style="display: none;">
                        <i class="fas fa-user-circle"></i>
                        <div class="customer-info">
                            <div class="customer-name">Juan Pérez</div>
                            <div class="customer-detail">Tel: 555-123-4567 | juan@example.com</div>
                        </div>
                        <span class="badge bg-success">Cliente Frecuente</span>
                        <button class="btn btn-sm btn-outline-secondary ms-2" id="btn-change-customer">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>

                    <div id="customer-search-area">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-8">
                                <div class="search-bar">
                                    <i class="fas fa-search"></i>
                                    <input type="text" class="form-control" id="customer-search" placeholder="Buscar cliente por nombre, num documento o correo...">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button class="btn btn-outline-primary flex-grow-1 btn-icon" id="btn-search-customer">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <button class="btn btn-primary flex-grow-1 btn-icon" id="btn-new-customer" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                                    <i class="fas fa-user-plus"></i> Nuevo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-boxes"></i> Productos
                </div>
                <div class="card-body">
                    <div class="product-grid" id="product-list">
                        <?= $productos ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-cart"></i> Carrito de Compra
                </div>
                <div class="card-body">
                    <div id="empty-cart" class="empty-state">
                        <i class="fas fa-shopping-basket"></i>
                        <p>El carrito está vacío</p>
                        <p class="small">Agrega productos haciendo clic en ellos</p>
                    </div>

                    <div id="cart-content" style="display: none;">
                        <div class="transaction-list" id="current-transaction-items">
                            <div class="transaction-item" data-id="1">
                                <div class="item-details">
                                    <div class="item-name">Smart TV 4K 50'</div>
                                    <div class="item-actions">
                                        <div class="quantity-control">
                                            <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <span class="quantity">1</span>
                                            <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                        <span class="item-remove"><i class="fas fa-trash-alt"></i></span>
                                    </div>
                                </div>
                                <div class="item-price">$499.99</div>
                            </div>
                            <div class="transaction-item" data-id="3">
                                <div class="item-details">
                                    <div class="item-name">Cafetera Automática</div>
                                    <div class="item-actions">
                                        <div class="quantity-control">
                                            <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                            <span class="quantity">2</span>
                                            <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                        </div>
                                        <span class="item-remove"><i class="fas fa-trash-alt"></i></span>
                                    </div>
                                </div>
                                <div class="item-price">$259.98</div>
                            </div>
                        </div>

                        <div class="total-section">
                            <div class="subtotal-row">
                                <span>Subtotal:</span>
                                <span>$759.97</span>
                            </div>
                            <div class="tax-row">
                                <span>IVA (16%):</span>
                                <span>$121.60</span>
                            </div>
                            <div class="total-row">
                                <span>Total:</span>
                                <span id="transaction-total">$881.57</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3"><i class="fas fa-coins me-2"></i> Forma de Pago</h5>
                            <div class="payment-methods">
                                <div class="payment-method active" data-method="cash">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Efectivo</span>
                                </div>
                                <div class="payment-method" data-method="card">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Tarjeta</span>
                                </div>
                                <div class="payment-method" data-method="transfer">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span>Transferencia</span>
                                </div>
                                <div class="payment-method" data-method="qr">
                                    <i class="fas fa-qrcode"></i>
                                    <span>Código QR</span>
                                </div>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-success btn-lg w-100 btn-complete-sale" id="btn-complete-sale">
                                <i class="fas fa-check-circle me-2"></i> Completar Venta
                            </button>
                            <div class="quick-actions">
                                <button class="btn btn-outline-danger btn-icon" id="btn-cancel-sale">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo cliente -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" aria-labelledby="newCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal para nuevo cliente (continuación) -->
            <div class="modal-header">
                <h5 class="modal-title" id="newCustomerModalLabel"><i class="fas fa-user-plus me-2"></i> Registrar Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="new-customer-form" class="row needs-validation" novalidate>
                    <?= $renderer->renderElement($newCustomerForm->getElement('project_id')) ?>
                    <fieldset class="gap-5">
                        <legend style="font-size: 1.3em !important;">Datos personales</legend>
                        <div class="row">
                            <div class="col-md-3">
                                <?= $renderer->renderElement($newCustomerForm->getElement('primer_nombre')) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $renderer->renderElement($newCustomerForm->getElement('segundo_nombre')) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $renderer->renderElement($newCustomerForm->getElement('primer_apellido')) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $renderer->renderElement($newCustomerForm->getElement('segundo_apellido')) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $renderer->renderElement($newCustomerForm->getElement('document_type_id')) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $renderer->renderElement($newCustomerForm->getElement('numero_identificacion')) ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="gap-5 mt-5">
                        <legend style="font-size: 1.3em !important;">Datos de contacto</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $renderer->renderElement($newCustomerForm->getElement('email')) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $renderer->renderElement($newCustomerForm->getElement('direccion')) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $renderer->renderElement($newCustomerForm->getElement('telefono')) ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="gap-5 mt-5">
                        <legend style="font-size: 1.3em !important;">Información de Envio</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $renderer->renderElement($newCustomerForm->getElement('nombre_contacto')) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $renderer->renderElement($newCustomerForm->getElement('direccion_envio')) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $renderer->renderElement($newCustomerForm->getElement('telefono_contacto')) ?>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-save-new-customer">
                    <i class="fas fa-save me-1"></i> Guardar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para finalizar venta -->
<!-- <div class="modal fade" id="completeSaleModal" tabindex="-1" aria-labelledby="completeSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeSaleModalLabel"><i class="fas fa-check-circle me-2"></i> Finalizar Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h6 class="mb-3">Detalles del pago</h6>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Método de pago</label>
                                        <div class="form-control-plaintext"><i class="fas fa-money-bill-wave me-2"></i> Efectivo</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Total a pagar</label>
                                        <div class="form-control-plaintext fs-5 fw-bold text-success"></div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="payment-amount" class="form-label">Monto recibido <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control" id="payment-amount" value="900.00">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payment-change" class="form-label">Cambio</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control bg-light" id="payment-change" value="18.43" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="mb-3">Opciones de comprobante</h6>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="receipt-print" checked>
                                    <label class="form-check-label" for="receipt-print">
                                        Imprimir ticket
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="receipt-email">
                                    <label class="form-check-label" for="receipt-email">
                                        Enviar por correo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="invoice-generate">
                                    <label class="form-check-label" for="invoice-generate">
                                        Generar factura
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="mb-3">Resumen de compra</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Smart TV 4K 50'</td>
                                <td class="text-center">1</td>
                                <td class="text-end">$499.99</td>
                                <td class="text-end">$499.99</td>
                            </tr>
                            <tr>
                                <td>Cafetera Automática</td>
                                <td class="text-center">2</td>
                                <td class="text-end">$129.99</td>
                                <td class="text-end">$259.98</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Subtotal:</th>
                                <th class="text-end">$759.97</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">IVA (16%):</th>
                                <th class="text-end">$121.60</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="text-end text-success">$881.57</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-confirm-sale">
                    <i class="fas fa-check-double me-1"></i> Confirmar Venta
                </button>
            </div>
        </div>
    </div>
</div> -->
<div class="modal fade" id="completeSaleModal" tabindex="-1" aria-labelledby="completeSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeSaleModalLabel"><i class="fas fa-check-circle me-2"></i> Finalizar Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-7">
                        <h6 class="mb-3">Información de Pagos</h6>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="mb-3">
                                    <label class="form-label d-block">Total de la Venta:</label>
                                    <div class="fs-4 fw-bold text-success" id="modal-sale-total">$881.57</div>
                                </div>
                                <hr>
                                <div class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-6">
                                        <label for="modal-payment-method" class="form-label">Método</label>
                                        <select class="form-select" id="modal-payment-method">
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="modal-payment-amount" class="form-label">Monto</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control" id="modal-payment-amount" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary w-100" id="btn-add-payment" title="Añadir Pago">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <ul class="list-group mb-3" id="current-payments-list">
                                    <li class="list-group-item text-muted text-center">No hay pagos registrados.</li>
                                </ul>
                                <div class="row fw-bold">
                                    <div class="col-md-6">Monto Pagado:</div>
                                    <div class="col-md-6 text-end" id="modal-amount-paid">$0.00</div>
                                </div>
                                <div class="row fw-bold mt-2">
                                    <div class="col-md-12 text-end" id="modal-change-due">Pendiente: $881.57</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <h6 class="mb-3">Opciones de comprobante</h6>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="receipt-print" checked>
                                    <label class="form-check-label" for="receipt-print">
                                        Imprimir ticket
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="receipt-email">
                                    <label class="form-check-label" for="receipt-email">
                                        Enviar por correo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="invoice-generate">
                                    <label class="form-check-label" for="invoice-generate">
                                        Generar factura
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="mb-3">Resumen de compra</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="completeSaleModalSummaryTableBody">
                        </tbody>
                        <tfoot id="completeSaleModalSummaryTableFoot">
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-confirm-sale">
                    <i class="fas fa-check-double me-1"></i> Confirmar Venta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para búsqueda rápida de productos -->
<div class="modal fade" id="quickSearchModal" tabindex="-1" aria-labelledby="quickSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickSearchModalLabel"><i class="fas fa-search me-2"></i> Búsqueda Rápida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="customer-display"></div>
                <div class="search-bar mb-3">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control form-control-lg" id="quick-search-input" placeholder="Buscar por nombre o código de barras..." autofocus>
                </div>
                <div id="quick-search-results" class="mt-3">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Smart TV 4K 50'</h6>
                                <span class="text-primary fw-bold">$499.99</span>
                            </div>
                            <p class="mb-1 small text-muted">SKU: TV-4K50 | Stock: 15</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Smartphone Galaxy S22</h6>
                                <span class="text-primary fw-bold">$799.00</span>
                            </div>
                            <p class="mb-1 small text-muted">SKU: SM-G22 | Stock: 8</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/POSSystem.js"></script>