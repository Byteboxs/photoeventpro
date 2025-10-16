/**
 * Clase para manejar eventos de formulario, validación, extracción de datos y envío con Fetch.
 * Utiliza FieldValidator y FormDataExtractor para la validación y extracción de datos respectivamente.
 */
class FormEventHandler {
  /**
   * Constructor de FormEventHandler.
   * @param {HTMLFormElement} form - El elemento formulario a manejar.
   * @param {FieldValidator} validator - Instancia de FieldValidator para la validación del formulario.
   * @param {FormDataExtractor} dataExtractor - Instancia de FormDataExtractor para extraer los datos del formulario.
   * @param {object} config - Objeto de configuración.
   *        @property {string} config.eventType - Tipo de evento para adjuntar el listener (ej: 'submit', 'click'). Default: 'submit'.
   *        @property {string} config.apiEndpoint - URL del endpoint de la API para enviar los datos del formulario. Obligatorio.
   *        @property {function} [config.onSuccess] - Función callback a ejecutar en caso de validación y envío exitoso. Recibe la respuesta del servidor.
   *        @property {function} [config.onError] - Función callback a ejecutar en caso de error en la validación o en el envío. Recibe el error.
   *        @property {function} [config.onValidationError] - Función callback a ejecutar específicamente en caso de error de validación del formulario. Recibe el objeto FormDataExtractor.
   *        @property {function} [config.onSubmitStart] - Función callback a ejecutar antes de iniciar el envío del formulario.
   */
  constructor(form, validator, dataExtractor, config) {
    /** @private @type {HTMLFormElement} */
    this.form = form;
    /** @private @type {FieldValidator} */
    this.validator = validator;
    /** @private @type {FormDataExtractor} */
    this.dataExtractor = dataExtractor;
    /** @private @type {object} */
    this.config = config;

    // Configuración por defecto para el tipo de evento si no se proporciona
    if (!this.config.eventType) {
      this.config.eventType = "submit";
    }

    this.setupEventListener();
  }

  /**
   * Configura el event listener en el formulario basado en el tipo de evento configurado.
   * @private
   */
  setupEventListener() {
    this.form.addEventListener(
      this.config.eventType,
      this.handleEvent.bind(this)
    );
  }

  /**
   * Maneja el evento configurado en el formulario.
   * Valida el formulario, extrae los datos y los envía mediante Fetch.
   * @private
   * @param {Event} event - El objeto evento.
   */
  async handleEvent(event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario (ej: submit normal)

    console.log("FormEventHandler: Antes de validator.validateForm()"); // NUEVO LOG
    const isValid = this.validator.validateForm();
    console.log(
      "FormEventHandler: Después de validator.validateForm(), isValid:",
      isValid
    ); // NUEVO LOG

    if (!isValid) {
      console.error("Error de validación en el formulario.");
      if (this.config.onValidationError) {
        this.config.onValidationError(this.dataExtractor); // Pasa FormDataExtractor para más información si es necesario
      }
      return; // Detener el proceso si la validación falla
    }

    const formData = this.dataExtractor.extract();

    if (this.config.onSubmitStart) {
      this.config.onSubmitStart(formData); // Callback antes del envío, útil para mostrar loaders, etc.
    }

    try {
      const response = await this.submitForm(formData);
      const contentType = response.headers.get("Content-Type"); // Obtener Content-Type de la respuesta

      if (response.ok) {
        let responseData;
        if (contentType && contentType.includes("application/json")) {
          responseData = await response.json(); // Intentar parsear como JSON si Content-Type indica JSON
        } else {
          responseData = await response.text(); // Si no es JSON, obtener como texto
        }
        console.log("Formulario enviado con éxito!", responseData);
        if (this.config.onSuccess) {
          this.config.onSuccess(responseData); // Pasar la respuesta (JSON o texto) al callback onSuccess
        }
      } else {
        let errorData;
        if (contentType && contentType.includes("application/json")) {
          errorData = await response.json(); // Intentar parsear error como JSON si Content-Type indica JSON
        } else {
          errorData = await response.text(); // Si no es JSON, obtener el error como texto
        }
        console.error(
          "Error al enviar el formulario. Código de estado:",
          response.status,
          errorData
        );
        if (this.config.onError) {
          this.config.onError({ status: response.status, data: errorData }); // Pasar info de error (JSON o texto) al callback onError
        }
      }
    } catch (error) {
      console.error("Error de red al enviar el formulario:", error);
      if (this.config.onError) {
        this.config.onError(error);
      }
    }
  }

  /**
   * Envía los datos del formulario utilizando Fetch API.
   * @private
   * @param {FormData} formData - Objeto FormData con los datos del formulario.
   * @returns {Promise<Response>} Promesa que resuelve con la respuesta del Fetch API.
   */
  async submitForm(formData) {
    if (!this.config.apiEndpoint) {
      throw new Error(
        "API Endpoint no configurado. Debes proporcionar `apiEndpoint` en la configuración."
      );
    }

    return fetch(this.config.apiEndpoint, {
      method: "POST", // o 'GET', 'PUT', 'DELETE' según tu API
      body: formData,
      // Si tu API requiere headers adicionales (ej: para autenticación o tipo de contenido no FormData)
      // puedes agregarlos aquí en el objeto headers: { ... }
    });
  }
}
