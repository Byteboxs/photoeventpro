/**
 * Interfaz para definir estrategias de extracción de datos de campos de formulario.
 * Define el contrato que deben cumplir todas las estrategias concretas.
 */
class ExtractionStrategy {
  /**
   * Método para extraer datos de un elemento del formulario y agregarlos a FormData.
   * @param {HTMLElement} element - El elemento del formulario del cual extraer los datos.
   * @param {FormData} formData - El objeto FormData al cual agregar los datos extraídos.
   * @abstract
   */
  extractData(element, formData) {
    throw new Error("Método abstracto `extractData` debe ser implementado.");
  }
}

/**
 * Estrategia concreta para extraer datos de campos de tipo 'input' de texto, email, etc.
 * Extiende la estrategia base ExtractionStrategy.
 */
class TextInputStrategy extends ExtractionStrategy {
  extractData(element, formData) {
    formData.append(element.name, element.value);
  }
}

/**
 * Estrategia concreta para extraer datos de campos de tipo 'textarea'.
 * Extiende la estrategia base ExtractionStrategy.
 */
class TextareaStrategy extends ExtractionStrategy {
  extractData(element, formData) {
    formData.append(element.name, element.value);
  }
}

/**
 * Estrategia concreta para extraer datos de campos de tipo 'select'.
 * Extiende la estrategia base ExtractionStrategy.
 */
class SelectStrategy extends ExtractionStrategy {
  extractData(element, formData) {
    formData.append(element.name, element.value);
  }
}

/**
 * Estrategia concreta para extraer datos de campos de tipo 'file'.
 * Maneja inputs de tipo 'file' individuales y múltiples.
 * Extiende la estrategia base ExtractionStrategy.
 */
class FileInputStrategy extends ExtractionStrategy {
  extractData(element, formData) {
    const files = element.files;
    if (element.multiple) {
      // Para inputs 'file' múltiples, agrega cada archivo individualmente
      for (let i = 0; i < files.length; i++) {
        formData.append(element.name, files[i]); // Usa element.name para todos los archivos del mismo input
      }
    } else if (files.length > 0) {
      // Para inputs 'file' individuales, agrega el archivo (si hay alguno)
      formData.append(element.name, files[0]);
    }
    // Si no hay archivos seleccionados, FormData simplemente no tendrá este campo,
    // lo cual es el comportamiento esperado en formularios web.
  }
}

/**
 * Clase principal para obtener datos de un formulario y construir un objeto FormData.
 * Utiliza el patrón Strategy para manejar diferentes tipos de campos de formulario.
 */
class FormDataExtractor {
  /**
   * Constructor de FormDataExtractor.
   * @param {HTMLFormElement} form - El elemento formulario del cual se extraerán los datos.
   * @param {object} [config={}] - Objeto de configuración opcional.
   *        @property {Array<string>} [config.excludedFields=[]] - Array de nombres de campos a excluir.
   */
  constructor(form, config = {}) {
    /** @private @type {HTMLFormElement} */
    this.form = form;
    /** @private @type {object} */
    this.config = config;
    /** @private @type {Map<string, ExtractionStrategy>} */
    this.extractionStrategies = this.createStrategies();
  }

  /**
   * Crea y registra las estrategias de extracción de datos disponibles.
   * @private
   * @returns {Map<string, ExtractionStrategy>} Mapa de estrategias por tipo de elemento.
   */
  createStrategies() {
    return new Map([
      ["INPUT_TEXT", new TextInputStrategy()],
      ["INPUT_EMAIL", new TextInputStrategy()],
      ["INPUT_NUMBER", new TextInputStrategy()],
      ["INPUT_URL", new TextInputStrategy()],
      ["INPUT_TEL", new TextInputStrategy()],
      ["TEXTAREA", new TextareaStrategy()],
      ["SELECT", new SelectStrategy()],
      ["INPUT_FILE", new FileInputStrategy()],
      // Puedes agregar más estrategias para otros tipos de campos si es necesario (ej: radio, checkbox, etc.)
    ]);
  }

  /**
   * Extrae los datos del formulario y los devuelve en un objeto FormData.
   * @returns {FormData} Objeto FormData con los datos del formulario.
   */
  extract() {
    const formData = new FormData();
    const elements = this.form.querySelectorAll("input, select, textarea"); // Selecciona todos los elementos de formulario relevantes

    elements.forEach((element) => {
      if (this.shouldExcludeField(element.name)) {
        return; // Saltar campos excluidos
      }

      const strategy = this.getStrategyForElement(element);
      if (strategy) {
        strategy.extractData(element, formData);
      } else {
        // Manejo por defecto para tipos no implementados en estrategias específicas
        console.warn(
          `No se encontró estrategia para el tipo de elemento: ${
            element.tagName
          } ${
            element.type || element.tagName
          }. Usando estrategia de texto por defecto.`
        );
        formData.append(element.name, element.value); // Estrategia por defecto para otros tipos como 'text'
      }
    });

    return formData;
  }

  /**
   * Determina si un campo debe ser excluido basándose en la configuración.
   * @private
   * @param {string} fieldName - El nombre del campo a verificar.
   * @returns {boolean} True si el campo debe ser excluido, false de lo contrario.
   */
  shouldExcludeField(fieldName) {
    return (
      this.config.excludedFields &&
      this.config.excludedFields.includes(fieldName)
    );
  }

  /**
   * Obtiene la estrategia de extracción de datos adecuada para un elemento del formulario.
   * @private
   * @param {HTMLElement} element - El elemento del formulario.
   * @returns {ExtractionStrategy | null} La estrategia de extracción o null si no se encuentra.
   */
  getStrategyForElement(element) {
    const elementType = this.getElementType(element);
    return this.extractionStrategies.get(elementType) || null;
  }

  /**
   * Determina el tipo de elemento para seleccionar la estrategia correcta.
   * @private
   * @param {HTMLElement} element - El elemento del formulario.
   * @returns {string} Tipo de elemento clave para buscar en el mapa de estrategias.
   */
  getElementType(element) {
    if (element.tagName.toLowerCase() === "input") {
      switch (element.type.toLowerCase()) {
        case "file":
          return "INPUT_FILE";
        case "email":
          return "INPUT_EMAIL";
        case "number":
          return "INPUT_NUMBER";
        case "url":
          return "INPUT_URL";
        case "tel":
          return "INPUT_TEL";
        case "text":
        default:
          return "INPUT_TEXT"; // 'text' es el tipo por defecto para <input>
      }
    } else if (element.tagName.toLowerCase() === "textarea") {
      return "TEXTAREA";
    } else if (element.tagName.toLowerCase() === "select") {
      return "SELECT";
    }
    return "UNKNOWN"; // Tipo desconocido o no soportado
  }
}
