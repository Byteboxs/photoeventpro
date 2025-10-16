/**
 * Clase genérica para manejar la carga de archivos mediante Drag and Drop.
 * Soporta cualquier tipo de archivo, carga única o múltiple, validación de tamaño,
 * previsualización (para imágenes) y construcción de la interfaz dentro de un contenedor.
 * Permite configuración de estilos CSS, posición del área de previsualización y tipos de archivo aceptados.
 */
class FileDragAndDropHandler {
  /**
   * Constructor de FileDragAndDropHandler.
   * @param {string} containerId - ID del contenedor HTML donde se insertará la interfaz de carga.
   * @param {object} config - Objeto de configuración.
   *        @property {boolean} [config.multiple=true] - Permite carga múltiple de archivos. Por defecto: true.
   *        @property {number} [config.maxFileSize=0] - Tamaño máximo permitido por archivo en bytes. 0 para sin límite. Por defecto: 0.
   *        @property {string} [config.previewPosition='bottom'] - Posición del área de previsualización: 'bottom', 'right', 'left'. Por defecto: 'bottom'.
   *        @property {string} [config.customStyles=''] - CSS personalizado para la interfaz. Si no se proporciona, se usan estilos por defecto.
   *        @property {string} [config.acceptedFileTypes='*'] - Tipos de archivo aceptados para el input file (atributo 'accept'). Por defecto: '*' (todos los tipos).
   *        @property {array<string>} [config.additionalAcceptedMimeTypes=[]] - Validación adicional programática de tipos MIME (lista de strings). Opcional.
   */
  constructor(containerId, config = {}) {
    /** @private @type {HTMLElement} */
    this.container = document.getElementById(containerId);
    /** @private @type {object} */
    this.config = config;
    /** @private @type {boolean} */
    this.multiple = config.multiple !== undefined ? config.multiple : true;
    /** @private @type {number} */
    this.maxFileSize = config.maxFileSize || 0;
    /** @private @type {string} */
    this.previewPosition = config.previewPosition || "bottom";
    /** @private @type {string} */
    this.customStyles = config.customStyles || "";
    /** @private @type {string} */
    this.acceptedFileTypes = config.acceptedFileTypes || "*";
    /** @private @type {array<string>} */
    this.additionalAcceptedMimeTypes = config.additionalAcceptedMimeTypes || []; // Validación adicional tipos MIME

    /** @private @type {HTMLElement} */
    this.dropArea = null;
    /** @private @type {HTMLInputElement} */
    this.fileInput = null;
    /** @private @type {HTMLElement} */
    this.filePreviewArea = null;

    this.buildInterface();
    this.setupEventListeners();
    this.applyStyles();
  }

  /**
   * Construye dinámicamente la interfaz de drag and drop dentro del contenedor especificado.
   * Organiza la posición del área de previsualización según la configuración y establece el atributo 'accept' del input file.
   * @private
   */
  buildInterface() {
    // Drop Area
    this.dropArea = document.createElement("div");
    this.dropArea.id = "file-drop-area";
    this.dropArea.classList.add("file-drop-area");

    const dropAreaText = document.createElement("p");
    dropAreaText.textContent =
      "Arrastra y suelta archivos aquí o haz clic para seleccionar";
    this.dropArea.appendChild(dropAreaText);

    // Input File (oculto)
    this.fileInput = document.createElement("input");
    this.fileInput.type = "file";
    this.fileInput.id = "file-input";
    this.fileInput.name = "files[]";
    this.fileInput.style.display = "none";
    if (this.multiple) {
      this.fileInput.multiple = true;
    }
    this.fileInput.accept = this.acceptedFileTypes; // Establecer el atributo accept aquí usando la configuración

    this.dropArea.appendChild(this.fileInput);

    // File Preview Area
    this.filePreviewArea = document.createElement("div");
    this.filePreviewArea.id = "file-preview-area";
    this.filePreviewArea.classList.add("file-preview-area");

    // Organizar la interfaz según la posición de previsualización
    if (this.previewPosition === "left" || this.previewPosition === "right") {
      // Layout horizontal: dropArea y filePreviewArea uno al lado del otro
      this.container.style.display = "flex";
      this.container.style.flexDirection = "row"; // Dirección principal horizontal

      if (this.previewPosition === "left") {
        this.container.appendChild(this.filePreviewArea);
        this.container.appendChild(this.dropArea);
      } else {
        // previewPosition === 'right' o por defecto si es inválido
        this.container.appendChild(this.dropArea);
        this.container.appendChild(this.filePreviewArea);
      }
      this.dropArea.style.flex = "1"; // Para que dropArea se expanda en el espacio disponible
      this.filePreviewArea.style.flex = "1"; // Igual para filePreviewArea en layout horizontal
    } else {
      // previewPosition === 'bottom' o cualquier otro valor inválido
      // Layout vertical por defecto: dropArea arriba, filePreviewArea abajo
      this.container.style.display = "block"; // Volver a display block para layout vertical
      this.container.appendChild(this.dropArea);
      this.container.appendChild(this.filePreviewArea);
    }
  }

  /**
   * Aplica los estilos CSS a la interfaz, usando los estilos personalizados si se proporcionan,
   * o los estilos por defecto si no hay estilos personalizados.
   * @private
   */
  applyStyles() {
    let stylesToApply = defaultStyles; // Usar estilos por defecto inicialmente

    if (this.config.customStyles) {
      stylesToApply = this.config.customStyles; // Usar estilos personalizados si se proporcionan
    }

    const styleSheet = document.createElement("style");
    styleSheet.type = "text/css";
    styleSheet.innerText = stylesToApply;
    document.head.appendChild(styleSheet);
  }

  /**
   * Configura los event listeners necesarios para la funcionalidad de drag and drop.
   * @private
   */
  setupEventListeners() {
    // Prevenir comportamientos de arrastre por defecto en el documento
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
      document.addEventListener(eventName, this.preventDefaults, false);
    });

    ["dragenter", "dragover"].forEach((eventName) => {
      this.dropArea.addEventListener(
        eventName,
        this.highlightDropArea.bind(this),
        false
      );
    });

    ["dragleave", "drop"].forEach((eventName) => {
      this.dropArea.addEventListener(
        eventName,
        this.unhighlightDropArea.bind(this),
        false
      );
    });

    this.dropArea.addEventListener("drop", this.handleDrop.bind(this), false);
    this.dropArea.addEventListener(
      "click",
      this.triggerFileInput.bind(this),
      false
    );
    this.fileInput.addEventListener(
      "change",
      this.handleFileInputChange.bind(this),
      false
    );
  }

  /**
   * Previene los comportamientos por defecto del navegador para eventos de drag and drop.
   * @private
   * @param {DragEvent} event - El evento de drag.
   */
  preventDefaults(event) {
    event.preventDefault();
    event.stopPropagation();
  }

  /**
   * Resalta el área de drop añadiendo una clase 'highlight'.
   * @private
   * @param {DragEvent} event - El evento de drag.
   */
  highlightDropArea(event) {
    this.dropArea.classList.add("highlight");
  }

  /**
   * Des-resalta el área de drop removiendo la clase 'highlight'.
   * @private
   * @param {DragEvent} event - El evento de drag.
   */
  unhighlightDropArea(event) {
    this.dropArea.classList.remove("highlight");
  }

  /**
   * Maneja el evento 'drop', procesa los archivos soltados.
   * @private
   * @param {DragEvent} event - El evento de drag.
   */
  handleDrop(event) {
    const files = event.dataTransfer.files;
    this.handleFiles(files);
    this.fileInput.files = files; // Asigna la FileList al input file oculto
  }

  /**
   * Dispara el evento 'click' en el input file para abrir el diálogo de selección de archivos.
   * @private
   */
  triggerFileInput() {
    this.fileInput.click();
  }

  /**
   * Maneja el evento 'change' del input file, procesa los archivos seleccionados.
   * @private
   */
  handleFileInputChange() {
    const files = this.fileInput.files;
    this.handleFiles(files);
    this.fileInput.files = files; // Asigna la FileList al input file oculto
  }

  /**
   * Maneja una FileList, valida cada archivo y actualiza el área de previsualización.
   * @private
   * @param {FileList} files - Lista de archivos a manejar.
   */
  handleFiles(files) {
    files = [...files]; // Convertir FileList a Array
    this.filePreviewArea.innerHTML = ""; // Limpiar previsualizaciones previas AL INICIO de handleFiles para evitar acumulación en errores

    if (!this.multiple && files.length > 0) {
      // Modo de carga única: limpiar previsualizaciones previas y mostrar solo el primero (o el último en este bucle)
      // Procesamos solo el último archivo en modo único para mejor UX en caso de selección múltiple
      const error = this.processFile(files[files.length - 1]); // processFile ahora devuelve el error, si lo hay
      if (error) {
        this.displayFileError(error); // Mostrar el error en la interfaz
      }
    } else {
      // Modo de carga múltiple: añadir nuevas previsualizaciones
      files.forEach((file) => {
        const error = this.processFile(file); // processFile ahora devuelve el error, si lo hay
        if (error) {
          this.displayFileError(error); // Mostrar el error en la interfaz
        }
      });
    }
  }

  /**
   * Procesa un solo archivo, validando su tamaño y tipo, y mostrando una previsualización (si es imagen).
   * @private
   * @param {File} file - El archivo a procesar.
   * @returns {string|null} Retorna un mensaje de error en string si hay un error de validación, o null si no hay error.
   */
  processFile(file) {
    if (this.maxFileSize > 0 && file.size > this.maxFileSize) {
      return `El archivo "${
        file.name
      }" excede el tamaño máximo permitido de ${this.formatFileSize(
        this.maxFileSize
      )}.`;
    }

    // Validación ADICIONAL de tipo de archivo (opcional, si quieres ser más estricto que 'accept')
    if (
      this.config.acceptedFileTypes !== "*" &&
      this.config.additionalAcceptedMimeTypes.length > 0
    ) {
      if (!this.config.additionalAcceptedMimeTypes.includes(file.type)) {
        return `Tipo de archivo no válido: "${
          file.name
        }". Tipos aceptados: ${this.config.additionalAcceptedMimeTypes.join(
          ", "
        )}`;
      }
    }

    this.previewFile(file);
    return null; // No hay error
  }

  /**
   * Muestra un mensaje de error en el área de previsualización.
   * @private
   * @param {string} errorMessage - Mensaje de error a mostrar.
   */
  displayFileError(errorMessage) {
    const errorElement = document.createElement("div");
    errorElement.classList.add("file-error-item"); // Clase para estilos de error
    errorElement.textContent = errorMessage;
    this.filePreviewArea.appendChild(errorElement);
  }

  /**
   * Previsualiza un archivo, mostrando una imagen si es de tipo imagen, o información del archivo si no lo es.
   * @private
   * @param {File} file - El archivo a previsualizar.
   */
  previewFile(file) {
    const previewItem = document.createElement("div");
    previewItem.classList.add("file-preview-item"); // Clase para estilos

    let previewContent;

    if (file.type.startsWith("image/")) {
      // Previsualización de imagen
      previewContent = document.createElement("img");
      const reader = new FileReader();
      reader.onloadend = () => {
        previewContent.src = reader.result;
      };
      reader.readAsDataURL(file);
      previewContent.alt = file.name; // Texto alternativo para accesibilidad
    } else {
      // Previsualización genérica para otros tipos de archivo
      previewContent = document.createElement("div");
      previewContent.classList.add("file-icon"); // Clase para icono de archivo genérico
      const fileInfo = document.createElement("p");
      fileInfo.textContent = `${file.name} (${this.formatFileSize(file.size)})`;
      previewContent.appendChild(fileInfo);
    }

    previewItem.appendChild(previewContent);
    this.filePreviewArea.appendChild(previewItem);
  }

  /**
   * Formatea el tamaño de un archivo en un formato legible por humanos (KB, MB, etc.).
   * @private
   * @param {number} bytes - Tamaño del archivo en bytes.
   * @returns {string} Tamaño del archivo formateado.
   */
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
}

// Estilos CSS por defecto -  AÑADIDO estilos para mensajes de error
const defaultStyles = `
  .file-drop-area {
      border: 2px dashed #ccc;
      border-radius: 20px;
      width: 100%;
      min-height: 150px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      cursor: pointer;
      padding: 20px;
      box-sizing: border-box;
  }

  .file-drop-area.highlight {
      border-color: #007bff;
  }

  .file-preview-area {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px;
  }

  .file-preview-item {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 8px;
      box-sizing: border-box;
      width: auto;
      max-width: 150px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
  }

  .file-preview-item img {
      max-width: 100%;
      height: auto;
      border-radius: 6px;
  }

  .file-preview-item .file-icon {
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px dashed #eee;
      border-radius: 50%;
      margin-bottom: 5px;
      font-size: 2em;
      color: #999;
  }

  .file-preview-item .file-icon::before {
      content: '📄';
  }

  .file-preview-item p {
      margin: 0;
      font-size: 0.9em;
      word-wrap: break-word;
  }

  /* Estilos para MENSAJES DE ERROR */
  .file-error-item {
      color: #dc3545; /* Rojo, color de error Bootstrap */
      border: 1px solid #f8d7da; /* Borde rojo claro */
      background-color: #f8d7da; /* Fondo rojo claro */
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 10px;
      font-size: 0.9em;
      text-align: center;
  }
`;
const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = defaultStyles;
document.head.appendChild(styleSheet);
