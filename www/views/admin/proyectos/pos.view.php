<style>
    .product-card {
        cursor: pointer;
        transition: all 0.3s;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .cart-item {
        border-left: 4px solid #0d6efd;
    }

    /* #pos-container {
        /* height: calc(100vh - 80px); 
    }

    */
</style>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"><?= $titulo ?></h4>

    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <div class="d-flex gap-4">
            <?php if (isset($isClienteIndividual)) { ?>
                <?= $linkDetalleCliente ?>
            <?php } ?>
            <?= $linkRegistrarCliente ?>
        </div>
    </div>
</div>

<!-- Main Container -->
<form class="row needs-validation" id="myForm" novalidate>
    <input type="hidden" name="project_id" id="project_id" value="<?= $project_id ?>">
    <div class="container-fluid" id="pos-container">
        <div class="row h-100">
            <!-- Left Panel - Products -->
            <div class="col-lg-8 p-3">
                <div class="my-5">
                    <i class="fas fa-shopping-cart"></i> Productos
                </div>
                <!-- Products Grid -->
                <div class="products-container">
                    <div class="row row-cols-2 row-cols-md-4 g-3">
                        <!-- Product Card Template -->
                        <?php if ($productos) { ?>
                            <?php foreach ($productos as $producto) { ?>
                                <div class="col">
                                    <div class="card h-100 product-card border border-info">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= $producto->servicio ?></h6>
                                            <p class="card-text text-primary fw-bold"><?= $producto->precio ?></p>
                                            <p class="card-text"><strong>Descripción</strong>: <?= $producto->descripcion ?></p>
                                            <small class="text-muted"></small>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="alert alert-warning" role="alert">
                                No existen servicios creados
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="my-5" id="formResponses"></div>
            </div>
            <!-- Right Panel - Cart -->
            <div class="col-lg-4 p-3">
                <!-- Client Information -->
                <div class="my-5">
                    <i class="fas fa-cash-register"></i> Registradora
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Cliente:</h5>
                        </div>
                        <?= $renderer->renderElement($form->getElement('customer_id')) ?>
                        <?php if (isset($isClienteIndividual)) { ?>
                            <strong><?= $nombreCliente ?></strong>
                        <?php } ?>
                        <div class="invalid-feedback" id="clientError">
                            Por favor, selecciona un cliente.
                        </div>
                    </div>
                </div>
                <!-- Shopping Cart -->
                <div class="cart-container mb-3">
                    <!-- Cart Item Template -->
                    <div class="card mb-2 cart-item">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Producto</h6>
                                    <small class="text-muted">Descripcion</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-3">Total</span>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- More cart items would be added dynamically -->
                </div>
                <!-- Totals and Payment -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fw-bold">
                            <span>Total:</span>
                            <span class="text-primary">0</span>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" type="submit" id="processPaymentButton">
                                <i class="bi bi-cash"></i> Procesar Pago
                            </button>
                            <?= $linkCancelar ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Seleccionar elementos del DOM
        const productCards = document.querySelectorAll('.product-card');
        const cartContainer = document.querySelector('.cart-container');
        const processPaymentButton = document.getElementById('processPaymentButton');
        const myForm = document.getElementById('myForm');
        const clientSelect = document.getElementById('customer_id'); // Select de clientes
        const projectInput = document.getElementById('project_id'); // Select de proyectos
        const clientError = document.getElementById('clientError'); // Div para el error
        const viewElementResponse = document.createElement('div');
        viewElementResponse.id = 'formResponses';
        myForm.appendChild(viewElementResponse);
        let cartItems = [];

        // Obtener el HTML inicial del cart-item template para restaurarlo cuando el carrito esté vacío
        const initialCartItemHTML = `
            <div class="card mb-2 cart-item">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Producto</h6>
                            <small class="text-muted">Descripcion</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="fw-bold me-3">Total</span>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // 2. Event listeners para las tarjetas de producto
        productCards.forEach(card => {
            card.addEventListener('click', () => {
                const productName = card.querySelector('.card-title').textContent;
                const productPriceText = card.querySelector('.card-text.text-primary.fw-bold').textContent;
                const productPrice = parseFloat(productPriceText.replace('$', '').replace('$', ''));


                const existingItem = cartItems.find(item => item.name === productName);
                if (existingItem) {
                    existingItem.quantity++; // Incrementa la cantidad si el item existe
                } else {
                    const newItem = {
                        name: productName,
                        price: productPrice,
                        quantity: 1
                    };
                    cartItems.push(newItem);
                }
                updateCartDisplay();
            });
        });

        // 3. Funciones para actualizar el carrito y el total (sin cambios con la adición de la plantilla inicial)
        function updateCartDisplay() {
            if (cartItems.length === 0) {
                // Si el carrito está vacío, restaura el HTML inicial del cart-item template
                cartContainer.innerHTML = initialCartItemHTML;
            } else {
                cartContainer.innerHTML = ''; // Limpia el contenedor solo si hay items
                cartItems.forEach(item => {
                    const cartItemDiv = document.createElement('div');
                    cartItemDiv.classList.add('card', 'mb-2', 'cart-item');
                    cartItemDiv.innerHTML = `
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">Precio: $${item.price.toFixed(2)}, Cantidad: ${item.quantity}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="fw-bold me-3">$${(item.price * item.quantity).toFixed(2)}</span>
                                <button class="btn btn-sm btn-outline-danger remove-from-cart" data-product-name="${item.name}">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                    cartContainer.appendChild(cartItemDiv);
                });

                const removeButtons = document.querySelectorAll('.remove-from-cart');
                removeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const productNameToRemove = button.dataset.productName;
                        cartItems = cartItems.filter(item => item.name !== productNameToRemove);
                        updateCartDisplay();
                    });
                });
            }


            updateTotal();
        }

        function updateTotal() {
            // ... (misma lógica)
            let subtotal = 0;
            cartItems.forEach(item => {
                subtotal += item.price * item.quantity; // Multiplica precio por cantidad
            });

            const totalSpan = document.querySelector('.card .fw-bold .text-primary');
            const subtotalSpan = document.querySelector('.card .mb-2 span:last-child');

            totalSpan.textContent = `$${subtotal.toFixed(2)}`;
            subtotalSpan.textContent = `$${subtotal.toFixed(2)}`;
        }

        // 4. Evento del botón "Procesar Pago" (MODIFICADO para enviar tambien el precio)
        processPaymentButton.addEventListener('click', (event) => {
            event.preventDefault(); // Evitar el envío del formulario por defecto

            // Validar el select de clientes
            if (clientSelect.value === "-1") {
                clientSelect.classList.add('is-invalid'); // Mostrar error de Bootstrap
                clientError.style.display = "block";
                return; // Detener el proceso si no hay cliente seleccionado
            } else {
                clientSelect.classList.remove('is-invalid'); // Quitar error si está validado
                clientError.style.display = "none";
            }

            if (cartItems.length === 0) {
                const alert = BootstrapAlertFactory.createAlert({
                    message: 'No hay productos en el carrito.',
                    type: 'warning',
                    dismissible: true,
                    icon: "fas fa-exclamation-triangle",
                });
                let responseDiv = document.getElementById("formResponses");
                responseDiv.innerHTML = "";
                responseDiv.appendChild(alert.generateAlert());
                return;
            }

            const botonEnviar = document.querySelector('#processPaymentButton');
            botonEnviar.disabled = true;
            const formData = new FormData();

            cartItems.forEach((item, index) => {
                formData.append(`items[${index}][name]`, item.name);
                formData.append(`items[${index}][quantity]`, item.quantity);
                formData.append(`items[${index}][price]`, item.price); // Agregado el precio al FormData
            });

            formData.append('client', clientSelect.value) // customer_id
            formData.append('project', projectInput.value) // project_id

            let trash = document.getElementById("trash").getAttribute("data-valor-base64");
            let appFolderAddress = AjaxHandler.decodificarBase64(trash);
            console.log(appFolderAddress);

            fetch(appFolderAddress + '/realizar-pago-efectivo', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        // Manejar errores HTTP (4xx, 5xx)
                        return response.text().then(text => { // Intentar obtener el texto del error
                            throw new Error(`Error ${response.status}: ${text}`);
                        });
                    }
                    // Determinar el tipo de contenido y procesarlo
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else if (contentType && contentType.includes('text/html')) {
                        return response.text(); // Devuelve texto HTML
                    } else if (contentType && contentType.includes('text/plain')) {
                        return response.text(); //Devuelve texto plano
                    } else {
                        return response.text(); // Manejo por defecto: texto
                        //throw new Error('Tipo de contenido no soportado: ' + contentType);
                    }
                })
                .then(data => {
                    // Mostrar la respuesta en el div formResponses
                    let responseDiv = document.getElementById("formResponses");
                    responseDiv.innerHTML = ''; // Limpiar contenido anterior

                    if (typeof data === 'object' && data !== null) {
                        // Si data es un objeto (JSON), formatearlo para mostrarlo
                        console.log(data);
                        // let responseText = JSON.stringify(data, null, 2); // Formatear JSON con indentación
                        // const pre = document.createElement('pre');
                        // pre.textContent = responseText;
                        // responseDiv.appendChild(pre);
                        // let responseDiv = document.getElementById("formResponses");
                        // responseDiv.innerHTML = '';
                        const alert = BootstrapAlertFactory.createAlert({
                            message: data.message,
                            type: 'success',
                            dismissible: true,
                            icon: "far fa-check-circle",
                        });
                        responseDiv.appendChild(alert.generateAlert());

                        if (data.status === "success") {
                            cartItems = [];
                            updateCartDisplay();
                            clientSelect.value = "";
                        } else if (data.status === "fail") {
                            let responseDiv = document.getElementById("formResponses");
                            responseDiv.innerHTML = '';
                            const alert = BootstrapAlertFactory.createAlert({
                                message: data.message || 'Ocurrió un error en la petición.',
                                type: 'danger',
                                dismissible: true,
                                icon: "far fa-times-circle",
                            });
                            responseDiv.appendChild(alert.generateAlert());
                        }
                    } else if (typeof data === 'string') {
                        // Si data es una cadena (texto plano o HTML), mostrarla directamente
                        responseDiv.innerHTML = data;
                    }
                    botonEnviar.disabled = false;

                })
                .catch(error => {
                    let responseDiv = document.getElementById("formResponses");
                    responseDiv.innerHTML = '';
                    const alert = BootstrapAlertFactory.createAlert({
                        message: error.message || 'Ocurrió un error en la petición.',
                        type: 'danger',
                        dismissible: true,
                        icon: "far fa-times-circle",
                    });
                    responseDiv.appendChild(alert.generateAlert());
                    botonEnviar.disabled = false;

                });
        });

        // Inicializa el carrito para que muestre el template inicial al cargar la página
        updateCartDisplay();
    });
</script>