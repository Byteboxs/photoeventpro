class SelectConBuscador {
  constructor(selectElement) {
    this.selectElement = selectElement;
    this.options = Array.from(this.selectElement.options);

    // Buscar el overlay dentro del mismo contenedor padre
    this.overlay =
      this.selectElement.parentNode.parentNode.querySelector(".search-overlay");

    // Comprobar si se encontró el overlay
    if (!this.overlay) {
      console.error("No se encontró el overlay para el select:", selectElement);
      return; // Salir del constructor si no se encuentra el overlay
    }

    this.searchInput = this.overlay.querySelector(".search-input");
    this.optionsContainer = this.overlay.querySelector(".options-container");
    this.debouncedFilter = this.debounce(this.filterOptions.bind(this), 300);

    this.initialize();
  }
  initialize() {
    if (!this.overlay) return;

    this.selectElement.addEventListener("click", () => {
      this.overlay.classList.toggle("show");
      if (this.overlay.classList.contains("show")) {
        this.searchInput.focus();
        this.filterOptions();
      }
    });

    // Detener la propagación del evento click en el input de búsqueda
    this.searchInput.addEventListener("click", (event) => {
      event.stopPropagation(); // Evita que el evento se propague al documento
    });

    this.searchInput.addEventListener("input", this.debouncedFilter);

    document.addEventListener("click", (event) => {
      if (!this.selectElement.parentNode.contains(event.target)) {
        this.overlay.classList.remove("show");
        this.searchInput.value = "";
        this.filterOptions();
      }
    });

    this.options.forEach((option) => {
      if (option.value !== "") {
        const optionElement = document.createElement("a");
        optionElement.classList.add("dropdown-item");
        optionElement.textContent = option.text;
        optionElement.dataset.value = option.value;
        optionElement.addEventListener("click", () => {
          this.selectElement.value = option.value;
          this.overlay.classList.remove("show");
          this.selectElement.dispatchEvent(new Event("change"));
        });
        this.optionsContainer.appendChild(optionElement);
      }
    });
  }

  filterOptions() {
    if (!this.overlay) return; // Salir si no hay overlay
    const searchTerm = this.searchInput.value.toLowerCase();
    const optionsElements = Array.from(
      this.optionsContainer.querySelectorAll(".dropdown-item")
    );
    optionsElements.forEach((option) => {
      const text = option.textContent.toLowerCase();
      option.style.display = text.includes(searchTerm) ? "block" : "none";
    });
  }

  debounce(func, delay) {
    let timeoutId;
    return function (...args) {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        func.apply(this, args);
      }, delay);
    };
  }
}

class AjaxHandler {
  constructor(url, responseCallback) {
    this.url = url;
    this.responseCallback = responseCallback;
  }
  static decodificarBase64(trash) {
    try {
      const cadenaDecodificada = atob(trash);
      return cadenaDecodificada;
    } catch (error) {
      // console.error("Error al decodificar:", error);
      return null;
    }
  }

  sendRequest(
    data = {},
    contentType = "application/x-www-form-urlencoded",
    isFileUpload = false
  ) {
    let ajaxOptions = {
      url: this.url,
      type: "POST",
      data: isFileUpload ? data : data,
      dataType: "text",
      contentType: contentType,
      success: (response, textStatus, jqXHR) => {
        // console.log("Respuesta AJAX exitosa:", response);
        let processedResponse = this.processResponse(
          response,
          jqXHR.getResponseHeader("Content-Type")
        );
        this.responseCallback(processedResponse);
      },
      error: (jqXHR, textStatus, errorThrown) => {
        // console.error("Error en la petición AJAX:", textStatus, errorThrown);
        let errorMessage = "Hubo un error en la solicitud.";
        let processedError = this.processResponse(
          jqXHR.responseText,
          jqXHR.getResponseHeader("Content-Type")
        );
        this.responseCallback({
          status: "error",
          message: errorMessage,
          details: processedError,
        });
      },
    };

    if (!isFileUpload) {
      // ajaxOptions.contentType = "application/json";
    } else {
      ajaxOptions.processData = false;
      ajaxOptions.contentType = false;
    }

    $.ajax(ajaxOptions);
  }

  processResponse(response, contentType) {
    if (contentType.includes("application/json")) {
      try {
        return JSON.parse(response);
      } catch (e) {
        // console.error("Error al parsear JSON:", e);
        return {
          status: "error",
          message: "Error al procesar la respuesta JSON del servidor",
          raw: response,
        };
      }
    } else if (contentType.includes("text/html")) {
      return {
        status: "success",
        message: "Respuesta HTML recibida",
        html: response,
      };
    } else if (contentType.includes("text/plain")) {
      return {
        status: "success",
        message: "Respuesta de texto recibida",
        text: response,
      };
    } else if (
      contentType.includes("application/xml") ||
      contentType.includes("text/xml")
    ) {
      return {
        status: "success",
        message: "Respuesta XML recibida",
        xml: response,
      };
    } else {
      console.warn("Tipo de contenido no reconocido:", contentType);
      return {
        status: "unknown",
        message: "Respuesta de tipo desconocido",
        raw: response,
      };
    }
  }
}

class FormDataHandler {
  constructor(form, ajaxHandler) {
    this.form = form;
    this.ajaxHandler = ajaxHandler;
  }

  handleSubmit() {
    const formData = this.getFormData();
    // console.log(formData);
    const hasFile = this.form.querySelector('input[type="file"]') !== null;
    if (hasFile) {
      const fileFormData = this.getRawFormData();
      this.ajaxHandler.sendRequest(fileFormData, true);
    } else {
      this.ajaxHandler.sendRequest(formData);
    }
  }

  // getFormData() {
  //   const formData = new FormData(this.form);
  //   const data = {};
  //   for (let [key, value] of formData.entries()) {
  //     if (value instanceof File) {
  //       continue;
  //     } else {
  //       data[key] = isNaN(value) ? value : Number(value);
  //     }
  //   }
  //   return data;
  // }

  getFormData() {
    const formData = new FormData(this.form);
    const data = {};
    for (let [key, value] of formData.entries()) {
      if (value instanceof File) {
        continue;
      } else {
        // Comprobación adicional para cadenas vacías
        data[key] = value === "" ? "" : isNaN(value) ? value : Number(value);
      }
    }
    return data;
  }

  getRawFormData() {
    return new FormData(this.form);
  }
}

class FormValidator {
  constructor(form) {
    this.form = form;
  }

  validateForm() {
    let isValid = true;
    this.clearErrors();
    const inputs = Array.from(
      this.form.querySelectorAll("input, textarea, select")
    ).filter(
      (input) =>
        input.type !== "submit" &&
        input.type !== "hidden" &&
        !this.shouldExclude(input)
    );

    inputs.forEach((input) => {
      if (!this.validateField(input)) {
        console.log("Validando campos");
        isValid = false;
      }
    });

    // Validar la confirmación de contraseña después de validar los demás campos
    if (!this.validatePasswordConfirmation()) {
      isValid = false;
    }

    return isValid;
  }

  validatePasswordConfirmation() {
    const passwordInput = this.form.querySelector(
      'input[type="password"]:not([name="confirm_password"]):not([id="confirm_password"])'
    );
    const confirmPasswordInput = this.form.querySelector(
      'input[name="confirm_password"], input[id="confirm_password"]'
    );

    if (passwordInput && confirmPasswordInput) {
      // Verificar si alguno de los campos está vacío
      if (
        passwordInput.value.trim() === "" ||
        confirmPasswordInput.value.trim() === ""
      ) {
        return true; // No validar si están vacíos, se considera "válido" en este caso.
      }

      if (passwordInput.value !== confirmPasswordInput.value) {
        this.showError(passwordInput, "Las contraseñas no coinciden.");
        this.showError(confirmPasswordInput, "Las contraseñas no coinciden.");
        return false;
      } else {
        this.showValid(passwordInput);
        this.showValid(confirmPasswordInput);
      }
    }
    return true; // Si no hay campos de confirmación, se considera válido
  }

  shouldExclude(input) {
    //Array de clases a excluir
    const excludedClasses = ["search-input", "no_validate"]; // Agrega aquí las clases que quieres excluir
    return excludedClasses.some((className) =>
      input.classList.contains(className)
    );
  }

  validateField(input) {
    let fieldIsValid = true;

    if (input.hasAttribute("required") && input.value.trim() === "") {
      this.showError(input, "Este campo es obligatorio.");
      fieldIsValid = false;
    }

    if (input.tagName.toLowerCase() === "select" && input.value === "-1") {
      this.showError(input, "Por favor, seleccione una opción válida.");
      fieldIsValid = false;
    }

    if (input.type === "email" && !this.isValidEmail(input.value)) {
      this.showError(input, "Por favor, ingrese un correo electrónico válido.");
      fieldIsValid = false;
    }

    if (
      input.type === "number" &&
      (isNaN(input.value) || input.value.trim() === "")
    ) {
      this.showError(input, "Este campo debe ser un número válido.");
      fieldIsValid = false;
    }

    if (input.type === "url" && !this.isValidURL(input.value)) {
      this.showError(input, "Por favor, ingrese una URL válida.");
      fieldIsValid = false;
    }

    if (input.type === "tel" && input.hasAttribute("pattern")) {
      const pattern = new RegExp(input.getAttribute("pattern"));
      if (!pattern.test(input.value)) {
        this.showError(
          input,
          "Por favor, ingrese un número de teléfono válido."
        );
        fieldIsValid = false;
      }
    }

    if (
      input.hasAttribute("minlength") &&
      input.value.length < parseInt(input.getAttribute("minlength"))
    ) {
      this.showError(
        input,
        `Este campo debe tener al menos ${input.getAttribute(
          "minlength"
        )} caracteres.`
      );
      fieldIsValid = false;
    }

    if (
      input.hasAttribute("maxlength") &&
      input.value.length > parseInt(input.getAttribute("maxlength"))
    ) {
      this.showError(
        input,
        `Este campo no puede tener más de ${input.getAttribute(
          "maxlength"
        )} caracteres.`
      );
      fieldIsValid = false;
    }

    if (
      input.hasAttribute("min") &&
      parseFloat(input.value) < parseFloat(input.getAttribute("min"))
    ) {
      this.showError(
        input,
        `El valor debe ser mayor o igual a ${input.getAttribute("min")}.`
      );
      fieldIsValid = false;
    }

    if (
      input.hasAttribute("max") &&
      parseFloat(input.value) > parseFloat(input.getAttribute("max"))
    ) {
      this.showError(
        input,
        `El valor debe ser menor o igual a ${input.getAttribute("max")}.`
      );
      fieldIsValid = false;
    }

    // Validación para campos de tipo "date"
    if (input.type === "date") {
      // console.log("Fecha:", input.value);
      // Verificar si el campo necesita estar en un día laboral
      if (input.hasAttribute("data-workday")) {
        if (!this.isValidWorkday(input.value)) {
          this.showError(
            input,
            "Por favor, seleccione un día laboral (lunes a sábado)."
          );
          fieldIsValid = false;
        }
      }
    }

    // if (input.type === "date") {
    //   console.log("Fecha:", input.value);
    //   if (!this.isValidWorkday(input.value)) {
    //     this.showError(
    //       input,
    //       "Por favor, seleccione un día laboral (lunes a sábado)."
    //     );
    //     fieldIsValid = false;
    //   }
    // }

    if (fieldIsValid) {
      this.showValid(input);
    }
    // if (fieldIsValid && input.type !== "password") {
    //   //No mostrar el campo valido en los passwords, solo en la confirmacion
    //   this.showValid(input);
    // }

    return fieldIsValid;
  }

  isValidWorkday(dateString) {
    const date = new Date(dateString);
    const dayOfWeek = date.getUTCDay();
    // console.log("Día de la semana:", dayOfWeek);
    return dayOfWeek >= 1 && dayOfWeek <= 6; // 0 es domingo, 6 es sábado
  }

  isValidEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
  }

  isValidURL(url) {
    try {
      new URL(url);
      return true;
    } catch (_) {
      return false;
    }
  }

  showError(input, message) {
    const existingValidMessage =
      input.parentNode.querySelector(".invalid-feedback");
    if (existingValidMessage) {
      existingValidMessage.remove();
    }
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    const error = document.createElement("div");
    error.classList.add("invalid-feedback");
    error.innerText = message;
    input.parentNode.appendChild(error);
  }

  showValid(input) {
    const existingValidMessage =
      input.parentNode.querySelector(".valid-feedback");
    if (existingValidMessage) {
      existingValidMessage.remove();
    }

    input.classList.add("is-valid");
    input.classList.remove("is-invalid");
    const validMessage = document.createElement("div");
    validMessage.classList.add("valid-feedback");
    validMessage.innerText = "Campo válido.";
    input.parentNode.appendChild(validMessage);
  }

  clearErrors() {
    const errorMessages = this.form.querySelectorAll(
      ".invalid-feedback, .valid-feedback"
    );
    errorMessages.forEach((error) => error.remove());

    const inputs = this.form.querySelectorAll(".is-invalid, .is-valid");
    inputs.forEach((input) => {
      input.classList.remove("is-invalid");
      input.classList.remove("is-valid");
    });
  }
}

class FormHandler {
  constructor(formId, submitUrl, responseCallback = null) {
    this.form = document.getElementById(formId);
    let trash = document
      .getElementById("trash")
      .getAttribute("data-valor-base64");

    this.submitUrl = this.decodificarBase64(trash) + submitUrl;
    // console.log("Submit URL:", this.submitUrl);
    this.viewElementResponse = null;

    this.responseCallback = responseCallback;
  }

  setViewElementResponse(response) {
    this.viewElementResponse = response;
  }
  decodificarBase64(trash) {
    try {
      const cadenaDecodificada = atob(trash);
      return cadenaDecodificada;
    } catch (error) {
      // console.error("Error al decodificar:", error);
      return null;
    }
  }

  init() {
    this.form.addEventListener("submit", this.handleSubmit.bind(this));
  }

  handleSubmit(event) {
    event.preventDefault();

    const botonEnviar = document.querySelector('button[type="submit"]');
    // console.log("Botón enviar:", botonEnviar);

    const validator = new FormValidator(this.form);
    let isValid = validator.validateForm();
    // console.log("Formulario validado:", isValid);

    if (isValid) {
      if (botonEnviar != null) {
        botonEnviar.disabled = true;
      }

      if (this.responseCallback) {
        const ajaxHandler = new AjaxHandler(
          this.submitUrl,
          this.responseCallback
        );
        const formDataHandler = new FormDataHandler(this.form, ajaxHandler);
        formDataHandler.handleSubmit();
      } else {
        const ajaxHandler = new AjaxHandler(this.submitUrl, (response) => {
          // console.log("Respuesta del servidor:", response);
        });
        const formDataHandler = new FormDataHandler(this.form, ajaxHandler);
        formDataHandler.handleSubmit();
      }
    } else {
      if (botonEnviar != null) {
        botonEnviar.disabled = false;
      }

      // console.log(
      //   "El formulario no es válido. Por favor, corrija los errores."
      // );
      // console.log("Tiene response:", this.viewElementResponse);
      if (this.viewElementResponse !== null) {
        // console.log("Tiene viewElementResponse:", this.viewElementResponse);
        this.viewElementResponse.innerHTML = "";
        // Crear una alerta de éxito
        const successAlert = BootstrapAlertFactory.createAlert({
          message: "¡Oops Algo no está bien. Por favor, corrija los errores.",
          type: "warning",
          dismissible: true,
          icon: "fas fa-exclamation-triangle", // Opcional: Bootstrap Icons o cualquier otro.
        });

        this.viewElementResponse.appendChild(successAlert.generateAlert());
      }
    }
  }
}

class BootstrapAlert {
  constructor(message, type = "primary", dismissible = false, icon = null) {
    this.message = message;
    this.type = type; // primary, secondary, success, danger, warning, info, light, dark
    this.dismissible = dismissible; // True si la alerta debe tener un botón de cerrar
    this.icon = icon; // Opcional: nombre del icono de Bootstrap o cualquier otro.
  }

  generateAlert() {
    // Contenedor principal
    const alertDiv = document.createElement("div");
    alertDiv.classList.add("alert", `alert-${this.type}`);

    if (this.dismissible) {
      alertDiv.classList.add("alert-dismissible", "fade", "show");
    }

    // Si hay un ícono, lo añadimos al principio
    if (this.icon) {
      const iconElement = document.createElement("i");
      // Añadir cada clase de icono correctamente
      const iconClasses = this.icon.split(" ");
      iconClasses.forEach((iconClass) => {
        iconElement.classList.add(iconClass);
      });

      alertDiv.appendChild(iconElement);
    }

    // Mensaje
    const messageSpan = document.createElement("span");
    messageSpan.innerText = ` ${this.message}`;
    alertDiv.appendChild(messageSpan);

    // Si la alerta es dismissible, añadimos el botón para cerrar
    if (this.dismissible) {
      const closeButton = document.createElement("button");
      closeButton.type = "button";
      closeButton.classList.add("btn-close");
      closeButton.setAttribute("data-bs-dismiss", "alert");
      closeButton.setAttribute("aria-label", "Close");
      alertDiv.appendChild(closeButton);
    }

    return alertDiv;
  }
}

class BootstrapAlertFactory {
  static createAlert(options = {}) {
    const { message, type, dismissible, icon } = options;
    return new BootstrapAlert(message, type, dismissible, icon);
  }
}

// Ejemplo de uso:
// document.addEventListener("DOMContentLoaded", () => {
//   const alertContainer = document.getElementById("alertContainer");

//   // Crear una alerta de éxito
//   const successAlert = BootstrapAlertFactory.createAlert({
//     message: "Operación realizada con éxito!",
//     type: "success",
//     dismissible: true,
//     icon: "bi bi-check-circle", // Opcional: Bootstrap Icons o cualquier otro.
//   });

//   // Añadir la alerta al DOM
//   alertContainer.appendChild(successAlert.generateAlert());

//   // Crear una alerta de advertencia
//   const warningAlert = BootstrapAlertFactory.createAlert({
//     message: "Cuidado, algo no está bien.",
//     type: "warning",
//     dismissible: false,
//     icon: "bi bi-exclamation-triangle", // Opcional
//   });

//   // Añadir la alerta al DOM
//   alertContainer.appendChild(warningAlert.generateAlert());
// });
