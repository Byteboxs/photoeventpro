class POSSystem {
  constructor(baseApiUrl) {
    // --- Estado de la Aplicación ---
    this.apiUrl = baseApiUrl; // URL base de tu API PHP (ej: '/api/v1/')
    this.currentTransaction = []; // Array de objetos: [{ product: {id, name, price, ...}, quantity: N }]
    this.selectedCustomer = null; // Objeto cliente: { id, name, phone, ...} o null
    // CAMBIO IMPORTANTE: Ahora es un array para múltiples pagos
    this.paymentTenders = []; // Array de objetos: [{ method: 'cash', amount: 100 }, { method: 'card', amount: 50 }]
    this.products = []; // Array de objetos producto cargados de la API (o simulados)
    this.customers = []; // Array de objetos cliente cargados de la API (o simulados)
    this.productItems = document.querySelectorAll(".product-item");
    this.allProducts = []; // Array para almacenar todos los productos (simulados o cargados)
    this.generalResponses = document.getElementById("generalResponses");

    // Paso 1: Crear los elementos principales (los que no dependen de otros)
    this.elements = {
      // Navbar
      currentTime: document.getElementById("current-time"),

      // Sección Cliente
      customerSelectedArea: document.getElementById("customer-selected"),
      customerSearchArea: document.getElementById("customer-search-area"),
      customerSearchInput: document.getElementById("customer-search"),
      btnSearchCustomer: document.getElementById("btn-search-customer"),
      btnNewCustomer: document.getElementById("btn-new-customer"),
      btnChangeCustomer: document.getElementById("btn-change-customer"),

      // Modal Nuevo Cliente
      newCustomerModalElement: document.getElementById("newCustomerModal"),
      newCustomerForm: document.getElementById("new-customer-form"),
      newCustomerNameInput: document.getElementById("new-customer-name"),
      newCustomerPhoneInput: document.getElementById("new-customer-phone"),
      newCustomerEmailInput: document.getElementById("new-customer-email"),
      newCustomerAddressInput: document.getElementById("new-customer-address"),
      newCustomerTypeSelect: document.getElementById("new-customer-type"),
      btnSaveNewCustomer: document.getElementById("btn-save-new-customer"),

      // Sección Productos
      productCategoriesContainer: document.querySelector(".product-categories"),
      productListContainer: document.getElementById("product-list"),

      // Sección Carrito/Venta Actual
      emptyCartState: document.getElementById("empty-cart"),
      cartContentArea: document.getElementById("cart-content"),
      transactionItemsContainer: document.getElementById(
        "current-transaction-items"
      ),
      subtotalDisplay: document.querySelector(".subtotal-row span:last-child"),
      taxDisplay: document.querySelector(".tax-row span:last-child"),
      transactionTotalDisplay: document.getElementById("transaction-total"),
      paymentMethodsContainer: document.querySelector(".payment-methods"), // Contenedor de selección de métodos

      // Botones de Acción
      btnCompleteSale: document.getElementById("btn-complete-sale"),
      btnCancelSale: document.getElementById("btn-cancel-sale"),

      // Modal Finalizar Venta - CAMBIOS CRÍTICOS AQUÍ
      completeSaleModalElement: document.getElementById("completeSaleModal"),
      // El total a pagar en el modal, ahora con el ID correcto
      completeSaleModalTotalDisplay:
        document.getElementById("modal-sale-total"), // <-- ¡ASEGÚRATE DE ESTE ID!

      // Elementos para el manejo de pagos
      modalPaymentMethodSelect: document.getElementById("modal-payment-method"), // Nuevo select para métodos
      modalPaymentAmountInput: document.getElementById("modal-payment-amount"), // Input para monto del pago actual
      btnAddPayment: document.getElementById("btn-add-payment"), // Botón para añadir pago
      currentPaymentsList: document.getElementById("current-payments-list"), // Lista de pagos añadidos
      modalAmountPaidDisplay: document.getElementById("modal-amount-paid"), // Total pagado hasta ahora
      modalChangeDueDisplay: document.getElementById("modal-change-due"), // Cambio o pendiente

      completeSaleModalSummaryTableBody: document.querySelector(
        "#completeSaleModal table tbody"
      ),
      completeSaleModalSummaryTableFoot: document.querySelector(
        "#completeSaleModal table tfoot"
      ),
      receiptPrintCheckbox: document.getElementById("receipt-print"),
      receiptEmailCheckbox: document.getElementById("receipt-email"),
      invoiceGenerateCheckbox: document.getElementById("invoice-generate"),
      btnConfirmSale: document.getElementById("btn-confirm-sale"),

      // Modal Búsqueda Rápida Producto
      quickSearchModalElement: document.getElementById("quickSearchModal"),
      quickSearchInput: document.getElementById("quick-search-input"),
      quickSearchResultsContainer: document
        .getElementById("quick-search-results")
        .querySelector(".list-group"),
    };

    // Paso 2: Crear los elementos que dependen de otros ya definidos
    this.elements.customerNameDisplay =
      this.elements.customerSelectedArea.querySelector(".customer-name");
    this.elements.customerDetailDisplay =
      this.elements.customerSelectedArea.querySelector(".customer-detail");

    // Inicializar modales de Bootstrap
    this.newCustomerModal = new bootstrap.Modal(
      this.elements.newCustomerModalElement
    );
    this.completeSaleModal = new bootstrap.Modal(
      this.elements.completeSaleModalElement
    );
    this.quickSearchModal = new bootstrap.Modal(
      this.elements.quickSearchModalElement
    );

    // --- Configuración y Datos (Simulados o para cargar de API) ---

    this.taxRate = 0.0; // Tasa de impuesto (ej: 16% para IVA en Colombia)
    // Categorías de productos simuladas (reemplazar por carga desde API)
    this.productCategories = ["Impresosor", "Digitales"];

    // Mapeo de métodos de pago para iconos y nombres (usado en el modal)
    this.paymentMethodIcons = {
      cash: {
        icon: "fas fa-money-bill-wave",
        name: "Efectivo",
      },
      card: {
        icon: "fas fa-credit-card",
        name: "Tarjeta",
      },
      transfer: {
        icon: "fas fa-exchange-alt",
        name: "Transferencia",
      },
      qr: {
        icon: "fas fa-qrcode",
        name: "Código QR",
      },
    };
  }

  // --- Inicialización ---
  init() {
    console.log("POSSystem: Inicializando...");
    this.initAllProductItems(); // Inicializar productos (reales)
    this.bindEvents(); // Configurar listeners de eventos
    this.updateTransactionDisplay(); // Actualizar la vista del carrito (inicialmente vacío)
    this.updateCustomerDisplay(); // Actualizar la vista del cliente (inicialmente sin seleccionar)
    this.updateTimeDisplay(); // Iniciar el reloj
    setInterval(this.updateTimeDisplay.bind(this), 60000); // Actualizar el reloj cada minuto
    this.populatePaymentMethodSelect(); // Rellenar el select de métodos de pago en el modal
  }
  initAllProductItems() {
    this.productItems.forEach((item) => {
      const id = item.getAttribute("data-id");
      const name = item.getAttribute("data-name");
      const price = parseFloat(item.getAttribute("data-price"));

      // Extraer stock
      const stockElement = item.querySelector(".item-stock");
      let stock = 0;
      if (stockElement) {
        const stockText = stockElement.textContent.trim();
        if (stockText.toLowerCase() === "en stock") {
          stock = 1; // O podrías poner un valor por defecto diferente si "En stock" significa una cantidad desconocida pero positiva.
        } else {
          const stockMatch = stockText.match(/\d+/);
          if (stockMatch) {
            stock = parseInt(stockMatch[0], 10);
          }
        }
      }

      // Extraer categoría
      const categoryElement = item.querySelector(".product-category");
      const category = categoryElement
        ? categoryElement.textContent.trim()
        : "";

      // Extraer icono o imagen (prioriza i tag con clase fas)
      const iconElement = item.querySelector(".product-image i");
      const imageElement = item.querySelector(".product-image img");
      let icon = "";
      if (iconElement && iconElement.classList.length > 0) {
        icon = Array.from(iconElement.classList).join(" ");
      } else if (imageElement) {
        // Si hay una imagen y no un ícono font-awesome, podrías manejarlo aquí.
        // Por ahora, lo dejaremos vacío si no es un ícono FAS.
        // Si quisieras la URL de la imagen: icon = imageElement.getAttribute('src');
      }

      // Generar un SKU simple (puedes ajustar esta lógica)
      const sku = name
        ? name.substring(0, 3).toUpperCase() + "-" + id
        : "SKU-" + id;

      // Ajustar la categoría si es necesario según tu ejemplo de salida
      let adjustedCategory = category;
      if (category === "Impresiones") {
        adjustedCategory = "Impresos";
      } else if (category === "Electrónicos") {
        adjustedCategory = "Digitales";
      } else if (category === "Hogar") {
        adjustedCategory = "Impreses"; // Basado en tu ejemplo, aunque parece un typo por "Impresos" o "Digitales"
      } else if (category === "Ropa") {
        adjustedCategory = "Digitales";
      }

      this.allProducts.push({
        id: id,
        sku: sku,
        name: name,
        price: price,
        stock: stock,
        category: adjustedCategory,
        icon: icon,
      });
    });
  }

  // --- Manejo de Eventos ---
  bindEvents() {
    console.log("POSSystem: Configurando eventos...");

    // Evento para añadir producto (usando delegación en el grid)
    this.elements.productListContainer.addEventListener("click", (event) => {
      console.log("Clic en producto");
      const productItem = event.target.closest(".product-item");

      if (productItem) {
        console.log("POSSystem: todos los productos:", this.allProducts);
        // Encuentra el producto completo usando el ID del dataset
        const productId = productItem.dataset.id;
        const product = this.allProducts.find((p) => p.id === productId);
        if (product) {
          this.addProduct(product);
        }
      }
    });

    // Evento para buscar cliente
    this.elements.btnSearchCustomer.addEventListener("click", () =>
      this.searchCustomer()
    );
    // Permitir buscar cliente presionando Enter en el input
    this.elements.customerSearchInput.addEventListener("keypress", (event) => {
      if (event.key === "Enter") {
        event.preventDefault(); // Prevenir envío de formulario por defecto
        this.searchCustomer();
      }
    });

    // Evento para cambiar/remover cliente seleccionado
    this.elements.btnChangeCustomer.addEventListener("click", () =>
      this.resetCustomerSelection()
    );

    // Evento para guardar nuevo cliente (en el modal)
    this.elements.btnSaveNewCustomer.addEventListener("click", () =>
      this.saveNewCustomer()
    );

    // Eventos para selección de método de pago (ya no se usa selectPaymentMethod directamente para añadir, solo para resaltar)
    // Se mantiene la funcionalidad visual para la sección del carrito principal, aunque el pago final sea en el modal.
    this.elements.paymentMethodsContainer.addEventListener("click", (event) => {
      const paymentMethodElement = event.target.closest(".payment-method");
      if (paymentMethodElement) {
        const method = paymentMethodElement.dataset.method;
        // Solo actualiza la selección visual en la pantalla principal, no el estado de pago.
        // La lógica de añadir pagos parciales está en el modal.
        this.elements.paymentMethodsContainer
          .querySelectorAll(".payment-method")
          .forEach((element) => {
            if (element.dataset.method === method) {
              element.classList.add("active");
            } else {
              element.classList.remove("active");
            }
          });
        console.log(`Método de pago resaltado: ${method}`);
      }
    });

    // Evento para abrir modal de finalizar venta
    this.elements.btnCompleteSale.addEventListener("click", () =>
      this.openCompleteSaleModal()
    );

    // Evento para confirmar la venta (en el modal de finalizar venta)
    this.elements.btnConfirmSale.addEventListener("click", () =>
      this.completeSale()
    );

    // Evento para cancelar venta
    this.elements.btnCancelSale.addEventListener("click", () =>
      this.cancelSale()
    );

    // Eventos de cantidad y eliminar en items de transacción (usando delegación)
    this.elements.transactionItemsContainer.addEventListener(
      "click",
      (event) => {
        console.log("Clic en item de transacción");
        const target = event.target;
        const transactionItemElement = target.closest(".transaction-item");

        if (!transactionItemElement) return; // Salir si el clic no fue dentro de un item de transacción

        const itemId = transactionItemElement.dataset.id;

        // Manejar botones de cantidad
        if (target.classList.contains("quantity-btn")) {
          const action = target.dataset.action;
          this.updateQuantity(itemId, action === "increase" ? 1 : -1);
        }

        // Manejar botón de eliminar
        if (target.closest(".item-remove")) {
          this.removeItem(itemId);
        }
      }
    );

    // CAMBIOS: Eventos para el manejo de múltiples pagos en el modal
    if (this.elements.btnAddPayment) {
      this.elements.btnAddPayment.addEventListener("click", () =>
        this.addPaymentTender()
      );
    }
    if (this.elements.modalPaymentAmountInput) {
      this.elements.modalPaymentAmountInput.addEventListener("input", () =>
        this.calculateModalChange()
      );
      this.elements.modalPaymentAmountInput.addEventListener(
        "keypress",
        (event) => {
          if (event.key === "Enter") {
            event.preventDefault(); // Prevenir envío de formulario por defecto
            this.addPaymentTender();
          }
        }
      );
    }
    // Evento para remover pagos de la lista
    if (this.elements.currentPaymentsList) {
      this.elements.currentPaymentsList.addEventListener("click", (event) => {
        const removeBtn = event.target.closest(".remove-payment-btn");
        if (removeBtn) {
          const paymentIndex = parseInt(removeBtn.dataset.index);
          this.removePaymentTender(paymentIndex);
        }
      });
    }

    // Eventos del Modal Nuevo Cliente (limpiar formulario al cerrar)
    this.elements.newCustomerModalElement.addEventListener(
      "hidden.bs.modal",
      () => {
        this.elements.newCustomerForm.reset();
      }
    );

    // Eventos del Modal Finalizar Venta (recalcular cambio al abrir)
    this.elements.completeSaleModalElement.addEventListener(
      "show.bs.modal",
      () => {
        this.resetPaymentModal(); // Resetear el estado de pagos del modal cada vez que se abre
        this.renderCompleteSaleModalSummary(); // Populatear la tabla resumen en el modal
      }
    );
    this.elements.completeSaleModalElement.addEventListener(
      "shown.bs.modal",
      () => {
        this.elements.modalPaymentAmountInput.focus(); // Enfocar el input de monto al abrir
      }
    );

    // Eventos del Modal Búsqueda Rápida Producto
    this.elements.quickSearchModalElement.addEventListener(
      "shown.bs.modal",
      () => {
        this.elements.quickSearchInput.focus(); // Enfocar el input al abrir
        this.elements.quickSearchInput.value = ""; // Limpiar búsqueda previa
        this.elements.quickSearchResultsContainer.innerHTML = ""; // Limpiar resultados previos
      }
    );
    this.elements.quickSearchInput.addEventListener("input", (event) => {
      const searchTerm = event.target.value.trim();
      // Si el término es lo suficientemente largo, hacer la búsqueda rápida
      if (searchTerm.length > 1) {
        this.quickSearchProducts(searchTerm);
      } else {
        this.elements.quickSearchResultsContainer.innerHTML = ""; // Limpiar si el término es muy corto
      }
    });

    // Evento para seleccionar un resultado de búsqueda rápida
    this.elements.quickSearchResultsContainer.addEventListener(
      "click",
      (event) => {
        const resultItem = event.target.closest(".list-group-item-action");
        if (resultItem) {
          event.preventDefault(); // Evitar navegación por defecto
          const productId = resultItem.dataset.id; // Asume que añades data-id al resultado

          const product = this.allProducts.find((p) => p.id === productId);
          if (product) {
            this.addProduct(product); // Añadir producto al carrito
            this.quickSearchModal.hide(); // Cerrar modal
          }
        }
      }
    );

    // Teclas de acceso rápido (ejemplo: Alt+B para búsqueda rápida)
    document.addEventListener("keydown", (e) => {
      // Alt+B para búsqueda rápida de productos
      if (e.altKey && e.key === "b") {
        e.preventDefault();
        this.quickSearchModal.show();
      }
      // Alt+P para abrir modal de completar venta
      if (e.altKey && e.key === "p") {
        e.preventDefault();
        this.openCompleteSaleModal(); // Llama al método que valida y abre el modal
      }
      // Alt+C para cancelar venta
      if (e.altKey && e.key === "c") {
        e.preventDefault();
        this.cancelSale();
      }
    });
  }

  // --- Métodos de Comunicación con la API (AJAX usando fetch y FormData) ---
  async fetchData(endpoint, method, formData) {
    const url = `${this.apiUrl}${endpoint}`;
    console.log(`POSSystem: Realizando POST a ${url}`);

    try {
      const response = await fetch(url, {
        method: "POST", // Forzamos POST según el requisito
        body: formData, // FormData es compatible con POST
      });

      if (!response.ok) {
        const errorBody = await response.text();
        console.error(
          `POSSystem: Error HTTP ${response.status} en ${url}`,
          errorBody
        );
        throw new Error(
          `Error de red: ${response.status} ${response.statusText}`
        );
      }

      const data = await response.json();
      console.log(`POSSystem: Respuesta de ${url}`, data);

      return data;
    } catch (error) {
      console.error(
        `POSSystem: Error al comunicarse con la API en ${url}`,
        error
      );
      throw error;
    }
  }

  // --- Métodos de Lógica de Negocio ---
  async searchCustomer() {
    const searchTerm = this.elements.customerSearchInput.value.trim();
    if (!searchTerm) {
      this.selectedCustomer = null;
      this.updateCustomerDisplay(
        "alert-info",
        "Por favor, busca o registra un cliente."
      );
      return;
    }

    const formData = new FormData();
    formData.append("searchTerm", searchTerm);

    const myAjax = new ModernAjaxHandler({});
    myAjax
      // .post(this.apiUrl + "/customers/search", formData, {})
      .post(this.apiUrl + "/customers/search", formData, {
        headers: {
          // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
        },
      })
      .then((response) => {
        console.log("Respuesta de la API:", response);
        if (typeof response === "object") {
          if (response.success) {
            this.generalResponses.innerHTML = "";
            const alert = BootstrapAlertFactory.createAlert({
              message: response.message,
              type: "success",
              dismissible: true,
              icon: "far fa-check-circle",
            });
            this.generalResponses.appendChild(alert.generateAlert());
            this.selectCustomer(response.customer);
            this.updateCustomerDisplay("alert-success", "");
          } else {
            this.generalResponses.innerHTML = "";
            const alert = BootstrapAlertFactory.createAlert({
              message: response.message,
              type: "warning",
              dismissible: true,
              icon: "fas fa-exclamation-triangle",
            });
            this.generalResponses.appendChild(alert.generateAlert());
          }
        } else {
          const alert = BootstrapAlertFactory.createAlert({
            message: response,
            type: "warning",
            dismissible: true,
            icon: "fas fa-exclamation-triangle",
          });
          this.generalResponses.innerHTML = "";
          this.generalResponses.appendChild(alert.generateAlert());
        }
      })
      .catch((error) => {
        const alert = BootstrapAlertFactory.createAlert({
          message:
            "Error general, intentelo de nuevo, si el problema persiste contacte al administrador",
          type: "danger",
          dismissible: true,
          icon: "fas fa-exclamation-triangle",
        });
        this.generalResponses.innerHTML = "";
        this.generalResponses.appendChild(alert.generateAlert());
      });
  }

  selectCustomer(customerData) {
    this.selectedCustomer = customerData;
    this.updateCustomerDisplay("alert-success", "");
    console.log("POSSystem: Cliente seleccionado", customerData);
    this.elements.customerSearchArea.style.display = "none";
    this.elements.customerSelectedArea.style.display = "flex";
  }

  resetCustomerSelection() {
    this.selectedCustomer = null;
    this.elements.customerSearchInput.value = "";
    this.updateCustomerDisplay(
      "alert-info",
      "Por favor, busca o registra un cliente."
    );
    this.elements.customerSelectedArea.style.display = "none";
    this.elements.customerSearchArea.style.display = "block";
    console.log("POSSystem: Selección de cliente restablecida.");
  }

  async saveNewCustomer() {
    const form = document.getElementById("new-customer-form");
    const validator = new FieldValidator(form);
    const isValid = validator.validateForm();
    if (isValid) {
      const formDataExtractor = new FormDataExtractor(form, {
        excludedFields: [],
      });
      const formData = formDataExtractor.extract();
      console.log("POSSystem: NewCustomerFor data: ", formData);

      const myAjax = new ModernAjaxHandler({});
      myAjax
        // .post(this.apiUrl + "/customers/new", formData, {})
        .post(this.apiUrl + "/customers/new", formData, {
          headers: {
            // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
          },
        })
        .then((response) => {
          console.log("Respuesta de la API:", response);
          if (typeof response === "object") {
            if (response.success) {
              this.generalResponses.innerHTML = "";
              const alert = BootstrapAlertFactory.createAlert({
                message: response.message,
                type: "success",
                dismissible: true,
                icon: "far fa-check-circle",
              });
              this.generalResponses.appendChild(alert.generateAlert());

              this.selectCustomer(response.customer);
              this.updateCustomerDisplay(
                "alert-success",
                `Nuevo cliente registrado y seleccionado: ${response.customer.name}`
              );
              this.newCustomerModal.hide();
              this.elements.newCustomerForm.reset();
              this.elements.customerSearchInput.value = "";
              validator.clearErrors();
            } else {
              this.generalResponses.innerHTML = "";
              const alert = BootstrapAlertFactory.createAlert({
                message: response.message,
                type: "warning",
                dismissible: true,
                icon: "fas fa-exclamation-triangle",
              });
              this.generalResponses.appendChild(alert.generateAlert());
              this.newCustomerModal.hide();
              this.elements.newCustomerForm.reset();
              this.elements.customerSearchInput.value = "";
              validator.clearErrors();
            }
          } else {
            const alert = BootstrapAlertFactory.createAlert({
              message: response,
              type: "warning",
              dismissible: true,
              icon: "fas fa-exclamation-triangle",
            });
            this.generalResponses.innerHTML = "";
            this.generalResponses.appendChild(alert.generateAlert());
          }
        })
        .catch((error) => {
          console.error("Error: ", error);
        });
    }
  }

  updateCustomerDisplay(alertClass = "alert-info", message = "") {
    console.log("POSSystem: Actualizando visualización de cliente...");
    const customerDisplay = this.elements.customerSelectedArea;
    const customerSearchArea = this.elements.customerSearchArea;
    const initialCustomerDisplay = document.getElementById("customer-display");

    if (this.selectedCustomer) {
      this.elements.customerNameDisplay.textContent =
        this.selectedCustomer.name;
      let details = [];
      if (this.selectedCustomer.phone)
        details.push(`Tel: ${this.selectedCustomer.phone}`);
      if (this.selectedCustomer.email)
        details.push(this.selectedCustomer.email);
      this.elements.customerDetailDisplay.textContent = details.join(" | ");
      const typeBadge = customerDisplay.querySelector(".badge");
      if (typeBadge && this.selectedCustomer.type) {
        typeBadge.textContent = this.selectedCustomer.type;
        typeBadge.classList.remove("bg-success", "bg-info", "bg-secondary");
        if (this.selectedCustomer.type === "VIP")
          typeBadge.classList.add("bg-info");
        else if (this.selectedCustomer.type === "Mayorista")
          typeBadge.classList.add("bg-secondary");
        else typeBadge.classList.add("bg-success");
        typeBadge.style.display = "inline-block";
      } else if (typeBadge) {
        typeBadge.style.display = "none";
      }
      customerSearchArea.style.display = "none";
      customerDisplay.style.display = "flex";
    } else {
      console.log("POSSystem: No hay cliente seleccionado.");
      customerSearchArea.style.display = "block";
      customerDisplay.style.display = "none";

      initialCustomerDisplay.textContent = message;
      initialCustomerDisplay.classList.remove(
        "alert-info",
        "alert-success",
        "alert-warning",
        "alert-danger"
      );
      initialCustomerDisplay.classList.add("alert", alertClass);
    }
  }

  renderCategories() {
    const container = this.elements.productCategoriesContainer;
    container.innerHTML = "";

    this.productCategories.forEach((category) => {
      const categoryElement = document.createElement("div");
      categoryElement.classList.add("category-pill");
      if (category === "Todos") {
        categoryElement.classList.add("active");
      }
      categoryElement.textContent = category;
      container.appendChild(categoryElement);
    });
  }

  filterProductsByCategory(category) {
    let filteredProducts = this.allProducts;
    if (category) {
      filteredProducts = this.allProducts.filter(
        (product) => product.category === category
      );
    }
    this.renderProducts(filteredProducts);
  }

  searchProducts(searchTerm) {
    const lowerSearchTerm = searchTerm.toLowerCase();
    const filteredProducts = this.allProducts.filter(
      (product) =>
        product.name.toLowerCase().includes(lowerSearchTerm) ||
        (product.sku && product.sku.toLowerCase().includes(lowerSearchTerm))
    );
    this.renderProducts(filteredProducts);
  }

  async quickSearchProducts(searchTerm) {
    const lowerSearchTerm = searchTerm.toLowerCase();
    const products = this.allProducts
      .filter(
        (product) =>
          product.name.toLowerCase().includes(lowerSearchTerm) ||
          (product.sku && product.sku.toLowerCase().includes(lowerSearchTerm))
      )
      .slice(0, 10);

    const container = this.elements.quickSearchResultsContainer;
    container.innerHTML = "";

    if (products.length === 0) {
      container.innerHTML =
        '<div class="list-group-item text-muted">No se encontraron resultados.</div>';
    } else {
      products.forEach((product) => {
        const resultItem = document.createElement("a");
        resultItem.href = "#";
        resultItem.classList.add("list-group-item", "list-group-item-action");
        resultItem.dataset.id = product.id;
        resultItem.innerHTML = `
                      <div class="d-flex w-100 justify-content-between">
                         <h6 class="mb-1">${product.name}</h6>
                         <span class="text-primary fw-bold">$${product.price.toFixed(
                           2
                         )}</span>
                     </div>
                     <p class="mb-1 small text-muted">SKU: ${
                       product.sku || "N/A"
                     } | Stock: ${
          product.stock !== undefined ? product.stock : "N/A"
        }</p>
                 `;
        container.appendChild(resultItem);
      });
    }
  }

  addProduct(product) {
    console.log("POSSystem: Añadiendo producto al carrito", product);
    const existingItem = this.currentTransaction.find(
      (item) => item.product.id === product.id
    );

    if (existingItem) {
      existingItem.quantity++;
    } else {
      this.currentTransaction.push({
        product: product,
        quantity: 1,
      });
    }

    this.updateTransactionDisplay();
    console.log(
      `POSSystem: Añadido "${product.name}" al carrito. Cantidad actual: ${
        existingItem ? existingItem.quantity : 1
      }`
    );
  }

  updateQuantity(productId, delta) {
    const itemIndex = this.currentTransaction.findIndex(
      (item) => item.product.id === productId
    );

    if (itemIndex > -1) {
      this.currentTransaction[itemIndex].quantity += delta;

      if (this.currentTransaction[itemIndex].quantity <= 0) {
        this.removeItem(productId);
      } else {
        this.updateTransactionDisplay();
      }
      console.log(
        `POSSystem: Cantidad de "${
          this.currentTransaction[itemIndex]?.product.name || productId
        }" actualizada a ${this.currentTransaction[itemIndex]?.quantity || 0}.`
      );
    }
  }

  removeItem(productId) {
    const initialLength = this.currentTransaction.length;
    this.currentTransaction = this.currentTransaction.filter(
      (item) => item.product.id !== productId
    );

    if (this.currentTransaction.length < initialLength) {
      this.updateTransactionDisplay();
      console.log(
        `POSSystem: Producto con ID "${productId}" eliminado del carrito.`
      );
    }
  }

  calculateTotals() {
    const subtotal = this.currentTransaction.reduce(
      (sum, item) => sum + item.product.price * item.quantity,
      0
    );
    const tax = subtotal * this.taxRate;
    const total = subtotal + tax;
    return {
      subtotal,
      tax,
      total,
    };
  }

  // --- NUEVOS MÉTODOS PARA MÚLTIPLES PAGOS ---

  // Rellena el select de métodos de pago en el modal
  populatePaymentMethodSelect() {
    const selectElement = this.elements.modalPaymentMethodSelect;
    selectElement.innerHTML = ""; // Limpiar opciones existentes

    for (const key in this.paymentMethodIcons) {
      const option = document.createElement("option");
      option.value = key;
      option.textContent = this.paymentMethodIcons[key].name;
      selectElement.appendChild(option);
    }
    // Seleccionar 'cash' por defecto si existe
    if (this.paymentMethodIcons.cash) {
      selectElement.value = "cash";
    }
  }

  // Añade un pago a la lista de pagos de la transacción
  addPaymentTender() {
    const method = this.elements.modalPaymentMethodSelect.value;
    const amount = parseFloat(this.elements.modalPaymentAmountInput.value);

    if (!method || isNaN(amount) || amount <= 0) {
      alert("Por favor, selecciona un método de pago y un monto válido.");
      return;
    }

    this.paymentTenders.push({
      method: method,
      amount: amount,
    });
    this.renderPaymentTenders(); // Actualizar la lista de pagos en el modal
    this.calculateModalChange(); // Recalcular cambio/pendiente
    this.elements.modalPaymentAmountInput.value = ""; // Limpiar el input
    this.elements.modalPaymentAmountInput.focus();
  }

  // Elimina un pago de la lista de pagos de la transacción
  //   removePaymentTender(index) {
  //     if (index >= 0 && index < this.paymentTenders.length) {
  //       this.paymentTenders.splice(index, 1);
  //       this.renderPaymentTenders();
  //       this.calculateModalChange();
  //     }
  //   }
  // Elimina un pago de la lista de pagos de la transacción
  removePaymentTender(index) {
    console.log(
      `removePaymentTender: Intentando eliminar pago en el índice ${index}.`
    );
    if (index >= 0 && index < this.paymentTenders.length) {
      const removedPayment = this.paymentTenders.splice(index, 1);
      console.log(
        `removePaymentTender: Pago eliminado: ${JSON.stringify(removedPayment)}`
      );
      this.renderPaymentTenders(); // Vuelve a renderizar la lista de pagos
      this.calculateModalChange(); // <-- ¡CRÍTICO! Vuelve a calcular el cambio/pendiente
      console.log(
        "removePaymentTender: Lista de pagos y cálculo de cambio actualizados."
      );
    } else {
      console.warn(
        `removePaymentTender: Índice ${index} fuera de rango. No se eliminó ningún pago.`
      );
    }
  }

  // Renderiza la lista de pagos añadidos en el modal
  renderPaymentTenders() {
    const listContainer = this.elements.currentPaymentsList;
    listContainer.innerHTML = ""; // Limpiar lista actual

    if (this.paymentTenders.length === 0) {
      listContainer.innerHTML =
        '<li class="list-group-item text-muted text-center">No hay pagos registrados.</li>';
      return;
    }

    this.paymentTenders.forEach((tender, index) => {
      const listItem = document.createElement("li");
      listItem.classList.add(
        "list-group-item",
        "d-flex",
        "justify-content-between",
        "align-items-center"
      );
      const iconInfo = this.paymentMethodIcons[tender.method] || {
        icon: "fas fa-question-circle",
        name: "Desconocido",
      };
      listItem.innerHTML = `
                <div>
                    <i class="${iconInfo.icon} me-2"></i>
                    ${iconInfo.name}: <strong>$${tender.amount.toFixed(
        2
      )}</strong>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-payment-btn" data-index="${index}">
                    <i class="fas fa-times"></i>
                </button>
            `;
      listContainer.appendChild(listItem);
    });
  }

  // Calcula el total pagado hasta ahora
  getAmountPaid() {
    return this.paymentTenders.reduce((sum, tender) => sum + tender.amount, 0);
  }

  // Calcula el cambio o el monto restante en el modal de finalizar venta
  //   calculateModalChange() {
  //     const total = this.getTotal();
  //     const amountPaid = this.getAmountPaid();
  //     const currentInputAmount =
  //       parseFloat(this.elements.modalPaymentAmountInput.value) || 0;

  //     const combinedPaid = amountPaid + currentInputAmount; // Total de pagos registrados + lo que está en el input actual

  //     const changeDue = combinedPaid - total;

  //     this.elements.modalAmountPaidDisplay.textContent = `$${amountPaid.toFixed(
  //       2
  //     )}`;

  //     // Mostrar "Cambio" o "Pendiente"
  //     if (changeDue >= 0) {
  //       this.elements.modalChangeDueDisplay.textContent = `Cambio: $${changeDue.toFixed(
  //         2
  //       )}`;
  //       this.elements.modalChangeDueDisplay.classList.remove("text-danger");
  //       this.elements.modalChangeDueDisplay.classList.add("text-success");
  //     } else {
  //       this.elements.modalChangeDueDisplay.textContent = `Pendiente: $${Math.abs(
  //         changeDue
  //       ).toFixed(2)}`;
  //       this.elements.modalChangeDueDisplay.classList.remove("text-success");
  //       this.elements.modalChangeDueDisplay.classList.add("text-danger");
  //     }
  //   }
  // Calcula el cambio o el monto restante en el modal de finalizar venta.
  // Este método se llama cada vez que se añade un pago o se modifica el input de monto.
  calculateModalChange() {
    console.log("calculateModalChange: Iniciando cálculo.");
    const total = this.getTotal();
    const amountPaid = this.getAmountPaid(); // Esto es la suma de los pagos ya agregados a this.paymentTenders

    // El currentInputAmount es el valor TEMPORAL que el usuario está escribiendo.
    // Solo se usa para mostrar el "posible" cambio/pendiente antes de añadir el pago.
    const currentInputAmount =
      parseFloat(this.elements.modalPaymentAmountInput.value) || 0;

    // Calculamos el total cubierto si este monto temporal se añadiera
    const combinedPaid = amountPaid + currentInputAmount;
    console.log(
      `calculateModalChange: Total Venta: $${total.toFixed(
        2
      )}, Pagado Registrado: $${amountPaid.toFixed(
        2
      )}, Monto en Input: $${currentInputAmount.toFixed(2)}`
    );
    console.log(
      `calculateModalChange: Pagado Combinado (registrado + input): $${combinedPaid.toFixed(
        2
      )}`
    );

    // Actualizar el display del monto total pagado (solo lo que ya está registrado)
    this.elements.modalAmountPaidDisplay.textContent = `$${amountPaid.toFixed(
      2
    )}`;

    // Calcular la diferencia para mostrar como "Cambio" o "Pendiente"
    // Usamos 'amountPaid' para esto, no 'combinedPaid', porque 'combinedPaid'
    // incluye un monto que aún no ha sido "confirmado" como pago.
    const difference = amountPaid - total; // Si es positivo es cambio, si es negativo es pendiente.
    console.log(
      `calculateModalChange: Diferencia (Pagado Registrado - Total): $${difference.toFixed(
        2
      )}`
    );

    // Mostrar "Cambio" o "Pendiente" basado en los pagos REGISTRADOS
    if (difference >= 0) {
      this.elements.modalChangeDueDisplay.textContent = `Cambio: $${difference.toFixed(
        2
      )}`;
      this.elements.modalChangeDueDisplay.classList.remove("text-danger");
      this.elements.modalChangeDueDisplay.classList.add("text-success");
      console.log(
        `calculateModalChange: Mostrando Cambio: $${difference.toFixed(2)}`
      );
    } else {
      this.elements.modalChangeDueDisplay.textContent = `Pendiente: $${Math.abs(
        difference
      ).toFixed(2)}`;
      this.elements.modalChangeDueDisplay.classList.remove("text-success");
      this.elements.modalChangeDueDisplay.classList.add("text-danger");
      console.log(
        `calculateModalChange: Mostrando Pendiente: $${Math.abs(
          difference
        ).toFixed(2)}`
      );
    }

    // Opcional: Si quieres que el input muestre el cambio potencial
    // cuando el usuario escribe el último pago, podríamos añadir una lógica aquí,
    // pero el enfoque principal es que el "Cambio" o "Pendiente" real
    // se refiera a los pagos ya CONFIRMADOS.
    // Por ahora, el input solo sirve para agregar.

    console.log("calculateModalChange: Cálculo completado.");
  }

  getTotal() {
    const totals = this.calculateTotals();
    return totals.total;
  }
  openCompleteSaleModal() {
    // Validar que haya un cliente seleccionado
    if (!this.selectedCustomer) {
      alert(
        "Debes seleccionar o registrar un cliente antes de completar la venta."
      );
      // Opcional: Resaltar el área de cliente
      this.elements.customerSearchInput.focus();
      return;
    }
    // Validar que haya productos en el carrito
    if (this.currentTransaction.length === 0) {
      alert("Agrega productos a la venta antes de completarla.");
      // Opcional: Resaltar el área de productos
      this.elements.productListContainer.scrollIntoView({
        behavior: "smooth",
      });
      return;
    }

    const total = this.getTotal(); // Calcula el total más reciente

    // Actualiza el display del total de la venta en el modal
    // Asegúrate de que este ID 'modal-sale-total' esté en tu HTML actualizado
    document.getElementById("modal-sale-total").textContent = `$${total.toFixed(
      2
    )}`;

    // Reiniciar los pagos y el campo de monto recibido para una nueva apertura del modal
    // ESTA ES LA LÍNEA CRÍTICA que llama al nuevo método para limpiar el estado de pagos del modal
    this.resetPaymentModal();

    // Renderizar el resumen de la transacción en la tabla del modal
    this.renderCompleteSaleModalSummary();

    // Mostrar el modal
    this.completeSaleModal.show();
    console.log("POSSystem: Abriendo modal de finalizar venta.");
  }

  //   openCompleteSaleModal() {
  //     if (!this.selectedCustomer) {
  //       alert(
  //         "Debes seleccionar o registrar un cliente antes de completar la venta."
  //       );
  //       this.elements.customerSearchInput.focus();
  //       return;
  //     }
  //     if (this.currentTransaction.length === 0) {
  //       alert("Agrega productos a la venta antes de completarla.");
  //       this.elements.productListContainer.scrollIntoView({
  //         behavior: "smooth",
  //       });
  //       return;
  //     }

  //     const total = this.getTotal();
  //     this.elements.completeSaleModalTotalDisplay.textContent = `$${total.toFixed(
  //       2
  //     )}`;

  //     // Reiniciar los pagos y el campo de monto recibido para una nueva apertura del modal
  //     this.resetPaymentModal(); // Se llama aquí para asegurar que siempre esté limpio al abrir

  //     this.renderCompleteSaleModalSummary();
  //     this.completeSaleModal.show();
  //     console.log("POSSystem: Abriendo modal de finalizar venta.");
  //   }

  // Resetea los campos y el estado de pagos del modal
  resetPaymentModal() {
    this.paymentTenders = []; // Limpiar todos los pagos
    this.elements.modalPaymentAmountInput.value = ""; // Limpiar input de monto
    this.populatePaymentMethodSelect(); // Asegurar que el select tenga opciones
    this.renderPaymentTenders(); // Renderizar lista de pagos vacía
    this.calculateModalChange(); // Recalcular cambio/pendiente a cero
  }

  renderCompleteSaleModalSummary() {
    const tbody = this.elements.completeSaleModalSummaryTableBody;
    const tfoot = this.elements.completeSaleModalSummaryTableFoot;
    tbody.innerHTML = "";

    this.currentTransaction.forEach((item) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                 <td>${item.product.name}</td>
                 <td class="text-center">${item.quantity}</td>
                 <td class="text-end">$${item.product.price.toFixed(2)}</td>
                 <td class="text-end">$${(
                   item.product.price * item.quantity
                 ).toFixed(2)}</td>
             `;
      tbody.appendChild(row);
    });

    const totals = this.calculateTotals();
    tfoot.innerHTML = `
             <tr>
                 <th colspan="3" class="text-end">Subtotal:</th>
                 <th class="text-end">$${totals.subtotal.toFixed(2)}</th>
             </tr>
             <tr>
                 <th colspan="3" class="text-end">IVA (${(
                   this.taxRate * 100
                 ).toFixed(0)}%):</th>
                 <th class="text-end">$${totals.tax.toFixed(2)}</th>
             </tr>
             <tr>
                 <th colspan="3" class="text-end">Total:</th>
                 <th class="text-end text-success">$${totals.total.toFixed(
                   2
                 )}</th>
             </tr>
         `;
  }

  async completeSale() {
    if (!this.selectedCustomer || this.currentTransaction.length === 0) {
      alert("Error: Cliente o productos faltantes.");
      this.completeSaleModal.hide();
      return;
    }

    const total = this.getTotal();
    const amountPaid = this.getAmountPaid();

    if (amountPaid < total) {
      alert("El monto total recibido es menor que el total de la venta.");
      return;
    }

    const formData = new FormData();

    this.currentTransaction.forEach((item, index) => {
      formData.append(`items[${index}][id]`, item.product.id);
      formData.append(`items[${index}][quantity]`, item.quantity);
      formData.append(`items[${index}][price]`, item.product.price.toFixed(2));
    });

    // Añadir todos los métodos de pago registrados
    this.paymentTenders.forEach((tender, index) => {
      formData.append(`payments[${index}][method]`, tender.method);
      formData.append(`payments[${index}][amount]`, tender.amount.toFixed(2));
    });

    const event_id = document.getElementById("my_event").value;
    const vendedor_id = document.getElementById("sales_person_id").value;

    formData.append("event_id", event_id);
    formData.append("user_id", this.selectedCustomer.id);
    formData.append("vendedor_id", vendedor_id);
    formData.append("total", total.toFixed(2));
    formData.append("amount_received", amountPaid.toFixed(2)); // Total pagado
    formData.append("change_given", Math.max(0, amountPaid - total).toFixed(2)); // Cambio dado (siempre positivo o cero)

    formData.append(
      "print_receipt",
      this.elements.receiptPrintCheckbox.checked ? "1" : "0"
    );
    formData.append(
      "email_receipt",
      this.elements.receiptEmailCheckbox.checked ? "1" : "0"
    );
    formData.append(
      "generate_invoice",
      this.elements.invoiceGenerateCheckbox.checked ? "1" : "0"
    );

    console.log("POSSystem: Datos de la venta a enviar: ", formData);

    const myAjax = new ModernAjaxHandler({});
    myAjax
      // .post(this.apiUrl + "/sales/process", formData, {})
      .post(this.apiUrl + "/sales/process", formData, {
        headers: {
          // No es necesario configurar 'Content-Type' para FormData, el navegador lo hace automáticamente
        },
      })
      .then((response) => {
        console.log("Respuesta de la API:", response);
        if (typeof response === "object") {
          if (response.success) {
            this.generalResponses.innerHTML = "";
            const alert = BootstrapAlertFactory.createAlert({
              message: response.message,
              type: "success",
              dismissible: true,
              icon: "far fa-check-circle",
            });
            this.generalResponses.appendChild(alert.generateAlert());
            this.completeSaleModal.hide();
            this.resetPOS();
          } else {
            this.generalResponses.innerHTML = "";
            const alert = BootstrapAlertFactory.createAlert({
              message: response.message,
              type: "warning",
              dismissible: true,
              icon: "fas fa-exclamation-triangle",
            });
            this.generalResponses.appendChild(alert.generateAlert());
            this.completeSaleModal.hide();
          }
        } else {
          const alert = BootstrapAlertFactory.createAlert({
            message: response,
            type: "warning",
            dismissible: true,
            icon: "fas fa-exclamation-triangle",
          });
          this.generalResponses.innerHTML = "";
          this.generalResponses.appendChild(alert.generateAlert());
          this.completeSaleModal.hide();
          this.resetPOS(); // Considera si quieres resetear POS incluso si la API devuelve un string de error.
        }
      })
      .catch((error) => {
        const alert = BootstrapAlertFactory.createAlert({
          message:
            "Error general, intentelo de nuevo, si el problema persiste contacte al administrador",
          type: "danger",
          dismissible: true,
          icon: "fas fa-exclamation-triangle",
        });
        this.generalResponses.innerHTML = "";
        this.generalResponses.appendChild(alert.generateAlert());
      });
  }

  cancelSale() {
    if (this.currentTransaction.length > 0 || this.selectedCustomer) {
      if (
        confirm(
          "¿Estás seguro de que deseas cancelar la venta actual? Se perderán los productos y el cliente asociado."
        )
      ) {
        this.resetPOS();
        console.log("POSSystem: Venta cancelada por el usuario.");
      }
    } else {
      alert("No hay una venta en curso para cancelar.");
    }
  }

  resetPOS() {
    this.currentTransaction = [];
    this.selectedCustomer = null;
    this.paymentTenders = []; // Restablecer pagos
    this.selectedPaymentMethod = "cash"; // Mantener este por si se usa en otro lugar para un default

    this.elements.customerSearchInput.value = "";
    this.elements.paymentMethodsContainer
      .querySelectorAll(".payment-method")
      .forEach((element, index) => {
        element.classList.remove("active");
        if (element.dataset.method === "cash") element.classList.add("active"); // Activar Efectivo por defecto
      });

    this.updateTransactionDisplay();
    this.updateCustomerDisplay(
      "alert-info",
      "Por favor, busca o registra un cliente."
    );
    console.log("POSSystem: Sistema POS reiniciado.");
  }

  // --- Métodos de Renderizado/Actualización de UI ---
  updateTransactionDisplay() {
    const itemsContainer = this.elements.transactionItemsContainer;
    const emptyState = this.elements.emptyCartState;
    const cartContent = this.elements.cartContentArea;

    itemsContainer.innerHTML = "";

    if (this.currentTransaction.length === 0) {
      emptyState.style.display = "block";
      cartContent.style.display = "none";
    } else {
      emptyState.style.display = "none";
      cartContent.style.display = "block";

      this.currentTransaction.forEach((item) => {
        const itemElement = document.createElement("div");
        itemElement.classList.add("transaction-item");
        itemElement.dataset.id = item.product.id;
        itemElement.innerHTML = `
                    <div class="item-details">
                        <div class="item-name">${item.product.name}</div>
                        <div class="item-actions">
                            <div class="quantity-control">
                                <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase">+</button>
                            </div>
                            <span class="item-remove" title="Eliminar"><i class="fas fa-trash-alt"></i></span>
                        </div>
                    </div>
                    <div class="item-price">$${(
                      item.product.price * item.quantity
                    ).toFixed(2)}</div>
                `;
        itemsContainer.appendChild(itemElement);
      });
    }

    this.updateTotalsDisplay();
  }

  updateTotalsDisplay() {
    const totals = this.calculateTotals();
    console.log("POSSystem: Totales actualizados", totals);
    this.elements.subtotalDisplay.textContent = `$${totals.subtotal.toFixed(
      2
    )}`;
    this.elements.taxDisplay.textContent = `$${totals.tax.toFixed(2)}`;
    this.elements.transactionTotalDisplay.textContent = totals.total.toFixed(2);
  }

  updateTimeDisplay() {
    const now = new Date();
    const timeString = now.toLocaleTimeString([], {
      hour: "2-digit",
      minute: "2-digit",
    });
    if (this.elements.currentTime) {
      this.elements.currentTime.textContent = timeString;
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const decoder = new Base64Decoder("trash"); // Asumo que Base64Decoder está definido globalmente
  let baseApiUrl = decoder.decode();
  console.log(baseApiUrl);
  const posSystem = new POSSystem(baseApiUrl);
  posSystem.init();
  console.log("Sistema POS completamente inicializado con JS OO.");
});
