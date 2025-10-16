class FieldValidator {
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
        // console.log("Validando campos");
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

    if (input.hasAttribute("required") && this.isEmptyValue(input)) {
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
      !this.hasMinLength(input.value, parseInt(input.getAttribute("minlength")))
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
      this.hasMaxLength(input.value, parseInt(input.getAttribute("maxlength")))
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
      !this.isGreaterThanOrEqual(
        input.value,
        parseFloat(input.getAttribute("min"))
      )
    ) {
      this.showError(
        input,
        `El valor debe ser mayor o igual a ${input.getAttribute("min")}.`
      );
      fieldIsValid = false;
    }

    if (
      input.hasAttribute("max") &&
      !this.isLessThanOrEqual(
        input.value,
        parseFloat(input.getAttribute("max"))
      )
    ) {
      this.showError(
        input,
        `El valor debe ser menor o igual a ${input.getAttribute("max")}.`
      );
      fieldIsValid = false;
    }

    // Validación para campos de tipo "date"
    if (input.type === "date") {
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

    // Validación para campos de tipo "file"
    if (input.type === "file") {
      fieldIsValid = this.validateFileField(input);
    }

    if (fieldIsValid) {
      this.showValid(input);
    }

    return fieldIsValid;
  }

  /**
   * Valida un campo de tipo 'file'.
   * @param {HTMLInputElement} input Elemento input de tipo 'file'.
   * @returns {boolean} Retorna true si el campo es válido, false si no.
   * @private
   */
  validateFileField(input) {
    let fieldIsValid = true;
    const files = input.files;

    if (input.hasAttribute("required") && files.length === 0) {
      this.showError(input, "Por favor, seleccione al menos un archivo.");
      fieldIsValid = false;
    }

    if (input.hasAttribute("accept") && files.length > 0) {
      const acceptedTypes = input
        .getAttribute("accept")
        .split(",")
        .map((type) => type.trim());
      for (let i = 0; i < files.length; i++) {
        if (!this.isFileTypeAccepted(files[i], acceptedTypes)) {
          this.showError(
            input,
            `Tipo de archivo no válido. Tipos aceptados: ${acceptedTypes.join(
              ", "
            )}.`
          );
          fieldIsValid = false;
          break; // Detenerse al primer archivo no válido
        }
      }
    }

    if (input.hasAttribute("data-max-size") && files.length > 0) {
      const maxSize = parseInt(input.getAttribute("data-max-size"), 10); // Tamaño máximo en bytes
      for (let i = 0; i < files.length; i++) {
        if (!this.isFileSizeValid(files[i], maxSize)) {
          this.showError(
            input,
            `El archivo "${
              files[i].name
            }" excede el tamaño máximo permitido de ${this.formatFileSize(
              maxSize
            )}.`
          );
          fieldIsValid = false;
          break; // Detenerse al primer archivo que excede el tamaño
        }
      }
    }

    if (input.hasAttribute("multiple")) {
      if (input.hasAttribute("data-min-files")) {
        const minFiles = parseInt(input.getAttribute("data-min-files"), 10);
        if (!this.hasMinFiles(files.length, minFiles)) {
          this.showError(
            input,
            `Por favor, seleccione al menos ${minFiles} archivos.`
          );
          fieldIsValid = false;
        }
      }

      if (input.hasAttribute("data-max-files")) {
        const maxFiles = parseInt(input.getAttribute("data-max-files"), 10);
        if (!this.hasMaxFiles(files.length, maxFiles)) {
          this.showError(
            input,
            `No puede seleccionar más de ${maxFiles} archivos.`
          );
          fieldIsValid = false;
        }
      }
    } else {
      //Para single file inputs
      if (input.hasAttribute("data-max-files") && files.length > 1) {
        this.showError(input, `Solo puede seleccionar un archivo.`);
        fieldIsValid = false;
      }
    }

    return fieldIsValid;
  }

  /**
   * Verifica si el valor de un input está vacío considerando el tipo de input (incluyendo 'file').
   * @param {HTMLInputElement} input Elemento input a verificar.
   * @returns {boolean} Retorna true si el valor está vacío, false si no.
   * @private
   */
  isEmptyValue(input) {
    if (input.type === "file") {
      return input.files.length === 0;
    }
    return input.value.trim() === "";
  }

  /**
   * Valida si el tamaño de un archivo es válido.
   * @param {File} file Archivo a validar.
   * @param {number} maxSize Tamaño máximo permitido en bytes.
   * @returns {boolean} Retorna true si el tamaño del archivo es válido, false si no.
   * @private
   */
  isFileSizeValid(file, maxSize) {
    return file.size <= maxSize;
  }

  /**
   * Valida si el tipo de archivo es aceptado.
   * @param {File} file Archivo a validar.
   * @param {array<string>} acceptedTypes Lista de tipos de archivo aceptados.
   * @returns {boolean} Retorna true si el tipo de archivo es válido, false si no.
   * @private
   */
  isFileTypeAccepted(file, acceptedTypes) {
    const fileType = file.type.toLowerCase();
    return acceptedTypes.some((acceptedType) => {
      // Manejar tipos MIME completos y extensiones (ej: image/*, .jpg)
      if (acceptedType.startsWith(".")) {
        return file.name.toLowerCase().endsWith(acceptedType);
      } else if (acceptedType.endsWith("/*")) {
        const baseType = acceptedType.slice(0, -2);
        return fileType.startsWith(baseType + "/");
      } else {
        return fileType === acceptedType;
      }
    });
  }

  /**
   * Valida si la cantidad de archivos seleccionados cumple con el mínimo requerido.
   * @param {number} fileCount Cantidad de archivos seleccionados.
   * @param {number} minFiles Cantidad mínima requerida de archivos.
   * @returns {boolean} Retorna true si cumple con el mínimo, false si no.
   * @private
   */
  hasMinFiles(fileCount, minFiles) {
    return fileCount >= minFiles;
  }

  /**
   * Valida si la cantidad de archivos seleccionados no excede el máximo permitido.
   * @param {number} fileCount Cantidad de archivos seleccionados.
   * @param {number} maxFiles Cantidad máxima permitida de archivos.
   * @returns {boolean} Retorna true si no excede el máximo, false si no.
   * @private
   */
  hasMaxFiles(fileCount, maxFiles) {
    return fileCount <= maxFiles;
  }

  isValidWorkday(dateString) {
    const date = new Date(dateString);
    const dayOfWeek = date.getUTCDay();
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

  hasMinLength(value, minLength) {
    return value.length >= minLength;
  }

  hasMaxLength(value, maxLength) {
    return value.length <= maxLength;
  }

  isGreaterThanOrEqual(value, min) {
    return parseFloat(value) >= min;
  }

  isLessThanOrEqual(value, max) {
    return parseFloat(value) <= max;
  }

  formatFileSize(bytes) {
    if (bytes < 1024) {
      return bytes + " bytes";
    } else if (bytes < 1048576) {
      return (bytes / 1024).toFixed(2) + " KB";
    } else if (bytes < 1073741824) {
      return (bytes / 1048576).toFixed(2) + " MB";
    } else {
      return (bytes / 1073741824).toFixed(2) + " GB";
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
