<style>
    #drop-area {
        border: 2px dashed #ccc;
        border-radius: 20px;
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        cursor: pointer;
    }

    #drop-area.highlight {
        border-color: #007bff;
    }

    #image-preview {
        display: flex;
        /* O display: inline-block; si prefieres esa opción */
        flex-wrap: wrap;
        /* Opcional: para que las imágenes se ajusten a varias líneas */
    }

    #image-preview img {
        margin: 5px;
        /* Espacio entre las imágenes */
    }
</style>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
    <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1"><?= $titulo ?></h4>
        <!-- <p class="mb-0">Decripción</p> -->
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
        <div class="d-flex gap-4">

            <?= $linkEventoClienteDetalle ?>
        </div>
    </div>
</div>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

    <div class="d-flex flex-column justify-content-center">
        <p class="mb-0"></p>
    </div>
    <div class="d-flex align-content-center flex-wrap gap-4">
    </div>
</div>

<form id="image-upload-form" class="card shadow-none border border-primary overflow-hidden" enctype="multipart/form-data">
    <div class="card-body">
        <h1>Cargar Imágenes</h1>
        <input type="hidden" id="proyecto_id" name="proyecto_id" value="<?= $proyecto_id ?>">
        <input type="hidden" id="cliente_id" name="cliente_id" value="<?= $cliente_id ?>">

        <div id="drop-area">
            <p>Arrastra y suelta imágenes aquí o haz clic para seleccionar</p>
            <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;">
        </div>
        <div id="image-preview"></div>
        <div id="upload-progress" class="progress" style="display:none;">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
        <div id="upload-message"></div>
        <div id="form-responses" class="mt-3"></div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-upload mx-1"></i> Subir Imágenes y Datos
        </button>
    </div>
</form>
<script>
    /**
     * Clase ImageRotator: Permite rotar y dibujar una imagen en un canvas con ángulo y dirección configurables.
     */
    class ImageRotator {
        /**
         * Rota y dibuja una imagen en un canvas.
         * Este método aplica las transformaciones necesarias al contexto del canvas y dibuja la imagen.
         * Es crucial que el canvas tenga las dimensiones adecuadas (ancho/alto intercambiados si es necesario)
         * ANTES de llamar a este método, para evitar recortes o distorsiones.
         * @param {HTMLImageElement} img - La imagen a rotar y dibujar.
         * @param {HTMLCanvasElement} canvas - El canvas donde dibujar.
         * @param {CanvasRenderingContext2D} ctx - El contexto 2D del canvas.
         * @param {number} angleDegrees - El ángulo de rotación en grados (ej: 90, 180, 270).
         * @param {'clockwise' | 'counter-clockwise'} [direction='clockwise'] - La dirección de la rotación ('clockwise' o 'counter-clockwise'). Por defecto es 'clockwise'.
         */
        rotateAndDraw(img, canvas, ctx, angleDegrees, direction = 'clockwise') {
            if (!img || !canvas || !ctx || typeof angleDegrees !== 'number') {
                console.error("ImageRotator: Argumentos inválidos proporcionados para rotateAndDraw.");
                return;
            }

            // Convertir ángulo de grados a radianes
            let angleRadians = angleDegrees * Math.PI / 180;

            // Ajustar para la dirección contra las manecillas del reloj
            if (direction === 'counter-clockwise') {
                angleRadians = -angleRadians;
            }

            // Guardar el estado actual del contexto del canvas
            ctx.save();

            // Mover el origen del contexto al centro del canvas
            ctx.translate(canvas.width / 2, canvas.height / 2);

            // Rotar el canvas
            ctx.rotate(angleRadians);

            // Dibujar la imagen centrada en el origen
            ctx.drawImage(img, -img.width / 2, -img.height / 2, img.width, img.height);

            // Restaurar el estado del contexto del canvas
            ctx.restore();

            console.log(`ImageRotator: Imagen dibujada con rotación de ${angleDegrees}° ${direction}.`);
        }

        /**
         * Versión mejorada que rota y dibuja una imagen en un canvas, manteniendo la proporción correcta.
         * Este método calcula la escala apropiada para llenar el canvas después de la rotación.
         * @param {HTMLImageElement} img - La imagen a rotar y dibujar.
         * @param {HTMLCanvasElement} canvas - El canvas donde dibujar.
         * @param {CanvasRenderingContext2D} ctx - El contexto 2D del canvas.
         * @param {number} angleDegrees - El ángulo de rotación en grados (ej: 90, 180, 270).
         * @param {'clockwise' | 'counter-clockwise'} [direction='clockwise'] - La dirección de la rotación.
         */
        rotateAndDrawImproved(img, canvas, ctx, angleDegrees, direction = 'clockwise') {
            if (!img || !canvas || !ctx || typeof angleDegrees !== 'number') {
                console.error("ImageRotator: Argumentos inválidos proporcionados para rotateAndDrawImproved.");
                return;
            }

            // Convertir ángulo de grados a radianes
            let angleRadians = angleDegrees * Math.PI / 180;

            // Ajustar para la dirección contra las manecillas del reloj
            if (direction === 'counter-clockwise') {
                angleRadians = -angleRadians;
            }

            // Guardar el estado actual del contexto del canvas
            ctx.save();

            // Mover el origen del contexto al centro del canvas
            ctx.translate(canvas.width / 2, canvas.height / 2);

            // Rotar el canvas
            ctx.rotate(angleRadians);

            // Calcular las dimensiones adecuadas para mantener la proporción
            let targetWidth, targetHeight;

            // Para rotaciones de 90° o 270°, necesitamos intercambiar dimensiones
            if (angleDegrees % 180 === 90) {
                // La relación de aspecto se invierte al rotar 90°/270°
                const widthRatio = canvas.height / img.width;
                const heightRatio = canvas.width / img.height;
                const scale = Math.min(widthRatio, heightRatio);

                targetWidth = img.width * scale;
                targetHeight = img.height * scale;
            } else {
                // Para rotaciones de 0° o 180°, mantenemos la relación original
                const widthRatio = canvas.width / img.width;
                const heightRatio = canvas.height / img.height;
                const scale = Math.min(widthRatio, heightRatio);

                targetWidth = img.width * scale;
                targetHeight = img.height * scale;
            }

            // Dibujar la imagen con las dimensiones calculadas
            ctx.drawImage(img, -targetWidth / 2, -targetHeight / 2, targetWidth, targetHeight);

            // Restaurar el estado del contexto del canvas
            ctx.restore();

            console.log(`ImageRotator: Imagen dibujada con rotación mejorada de ${angleDegrees}° ${direction}. Dimensiones escaladas: ${targetWidth}x${targetHeight}.`);
        }
    }
    /**
     * Clase OrientationDetector: Permite detectar la orientación de un elemento de imagen.
     */
    class OrientationDetector {
        /**
         * Detecta la orientación de un elemento de imagen HTML cargado.
         * @param {HTMLImageElement} img - El elemento de imagen HTML cargado (con width y height disponibles).
         * @returns {'horizontal' | 'vertical' | 'square' | 'unknown'} - La orientación detectada.
         */
        detect(img) {
            if (!img || typeof img.width !== 'number' || typeof img.height !== 'number' || img.width <= 0 || img.height <= 0) {
                console.error("OrientationDetector: Elemento de imagen no válido o no cargado.");
                return 'unknown';
            }

            if (img.width > img.height) {
                return 'horizontal';
            } else if (img.height > img.width) {
                return 'vertical';
            } else {
                return 'square'; // O width === height
            }
        }
    }
    /**
     * Clase ImageCompressor: Responsable de la compresión de imágenes.
     */
    // class ImageCompressor {
    //     constructor(defaultQuality = 0.5, defaultMaxWidth = 800) {
    //         this.defaultQuality = defaultQuality;
    //         this.defaultMaxWidth = defaultMaxWidth;
    //     }

    //     /**
    //      * Comprime un archivo de imagen.
    //      * @param {File} imageFile - El archivo de imagen a comprimir.
    //      * @param {object} options - Opciones de compresión (quality, maxWidth).
    //      * @returns {Promise<Blob|null>} - Promesa que resuelve con el Blob comprimido o null en caso de error.
    //      */
    //     compress(imageFile, options = {}) {
    //         return new Promise((resolve, reject) => {
    //             console.log("ImageCompressor: Iniciando compresión para", imageFile.name);
    //             const quality = options.quality !== undefined ? options.quality : this.defaultQuality;
    //             const maxWidth = options.maxWidth !== undefined ? options.maxWidth : this.defaultMaxWidth;

    //             const reader = new FileReader();

    //             reader.onload = (event) => {
    //                 const img = new Image();
    //                 img.src = event.target.result;

    //                 img.onload = () => {
    //                     console.log("ImageCompressor: Dimensiones originales:", img.width, img.height);
    //                     const canvas = document.createElement('canvas');
    //                     let width = img.width;
    //                     let height = img.height;

    //                     if (maxWidth && width > maxWidth) {
    //                         height = (maxWidth / width) * height;
    //                         width = maxWidth;
    //                     }

    //                     canvas.width = width;
    //                     canvas.height = height;
    //                     const ctx = canvas.getContext('2d');
    //                     ctx.drawImage(img, 0, 0, width, height);
    //                     console.log("ImageCompressor: Dimensiones redimensionadas:", width, height);

    //                     canvas.toBlob(blob => {
    //                         if (blob) {
    //                             console.log("ImageCompressor: Compresión exitosa para", imageFile.name, "Tamaño comprimido:", blob.size);
    //                             // Blob temporal
    //                             let url = URL.createObjectURL(blob);
    //                             let tempImg = new Image();
    //                             tempImg.onload = function() {
    //                                 console.log('Temporary Image width: ', tempImg.width);
    //                                 console.log('Temporary Image height: ', tempImg.height);
    //                                 URL.revokeObjectURL(url);
    //                             };
    //                             tempImg.src = url;



    //                             resolve(blob);
    //                         } else {
    //                             console.error("ImageCompressor: Fallo al comprimir con canvas.toBlob para", imageFile.name);
    //                             reject(new Error("Canvas toBlob falló."));
    //                             resolve(null);
    //                         }
    //                         // Limpiar referencias para liberar memoria después de usar blob
    //                         canvas.width = 0; // Liberar recursos de canvas context
    //                         canvas.height = 0;
    //                     }, 'image/jpeg', quality);

    //                     // Limpiar referencias para liberar memoria después de usar img
    //                     img.onload = null; // Eliminar event listener para img.onload
    //                     img.onerror = null; // Eliminar event listener para img.onerror
    //                     img.src = ''; // Liberar la URL en memoria
    //                 };

    //                 img.onerror = () => {
    //                     console.error("ImageCompressor: Error al cargar imagen", imageFile.name);
    //                     reject(new Error("Error al cargar la imagen."));
    //                     resolve(null);
    //                     // Limpiar referencias en caso de error al cargar la imagen
    //                     img.onload = null;
    //                     img.onerror = null;
    //                     img.src = '';
    //                 };
    //                 // Limpiar referencia a event para FileReader event listener
    //                 reader.onload = null;
    //                 reader.onerror = null;
    //                 // reader = null; // Liberar referencia al FileReader
    //             };

    //             reader.onerror = () => {
    //                 console.error("ImageCompressor: Error FileReader al leer", imageFile.name);
    //                 reject(new Error("FileReader error."));
    //                 resolve(null);
    //                 // Limpiar referencias en caso de error de FileReader
    //                 reader.onload = null;
    //                 reader.onerror = null;
    //                 // reader = null; // Liberar referencia al FileReader
    //             };
    //             reader.readAsDataURL(imageFile);
    //         });
    //     }
    // }

    /**
     * Clase ImageCompressor: Responsable de la compresión y rotación automática
     * de imágenes horizontales.
     */
    class ImageCompressor {
        constructor(defaultQuality = 0.5, defaultMaxWidth = 800) {
            this.defaultQuality = defaultQuality;
            this.defaultMaxWidth = defaultMaxWidth;
            // Instanciar el detector de orientación
            this.orientationDetector = new OrientationDetector();
            // Instanciar el rotador de imágenes
            this.imageRotator = new ImageRotator();
        }

        /**
         * Comprime un archivo de imagen.
         * Detecta la orientación y rota automáticamente las imágenes horizontales 90 grados a la derecha.
         * Ajusta las dimensiones del canvas para acomodar la imagen rotada antes de redimensionar con maxWidth.
         * @param {File} imageFile - El archivo de imagen a comprimir.
         * @param {object} options - Opciones de compresión (quality, maxWidth).
         * @returns {Promise<Blob|null>} - Promesa que resuelve con el Blob comprimido o null en caso de error.
         */
        compress(imageFile, options = {}) {
            return new Promise((resolve, reject) => {
                console.log("ImageCompressor: Iniciando compresión para", imageFile.name);
                const quality = options.quality !== undefined ? options.quality : this.defaultQuality;
                const maxWidth = options.maxWidth !== undefined ? options.maxWidth : this.defaultMaxWidth;

                const reader = new FileReader();

                reader.onload = (event) => {
                    const img = new Image();
                    img.src = event.target.result;

                    img.onload = () => {
                        const originalWidth = img.width;
                        const originalHeight = img.height;
                        console.log("ImageCompressor: Dimensiones originales:", originalWidth, originalHeight);

                        // Usar el detector de orientación
                        const orientation = this.orientationDetector.detect(img);
                        console.log(`ImageCompressor: Orientación detectada para ${imageFile.name}: ${orientation}`);

                        // Determinar si la imagen necesita rotación
                        const needsRotation = (orientation === 'horizontal');

                        // Primero definir las dimensiones base según la orientación
                        let finalWidth, finalHeight;

                        if (needsRotation) {
                            // Si vamos a rotar una imagen horizontal, intercambiamos dimensiones
                            finalWidth = originalHeight;
                            finalHeight = originalWidth;
                            console.log("ImageCompressor: Detectada orientación horizontal, intercambiando dimensiones para rotación:", finalWidth, finalHeight);
                        } else {
                            // Si no requiere rotación, mantener dimensiones originales
                            finalWidth = originalWidth;
                            finalHeight = originalHeight;
                        }

                        // Ahora aplicar maxWidth a las dimensiones finales (post-rotación)
                        if (maxWidth && finalWidth > maxWidth) {
                            const scale = maxWidth / finalWidth;
                            finalWidth = maxWidth;
                            finalHeight = Math.round(finalHeight * scale);
                            console.log("ImageCompressor: Aplicando redimensionamiento por maxWidth. Nuevas dimensiones:", finalWidth, finalHeight);
                        }

                        // Asegurarse de que las dimensiones sean números enteros para el canvas
                        finalWidth = Math.round(finalWidth);
                        finalHeight = Math.round(finalHeight);

                        const canvas = document.createElement('canvas');
                        canvas.width = finalWidth;
                        canvas.height = finalHeight;
                        const ctx = canvas.getContext('2d');

                        console.log("ImageCompressor: Dimensiones finales del canvas:", canvas.width, canvas.height);

                        // Dibujar la imagen en el canvas, aplicando rotación si es necesario
                        if (needsRotation) {
                            // Usar el rotador mejorado para dibujar la imagen girada y escalada correctamente
                            this.imageRotator.rotateAndDrawImproved(img, canvas, ctx, 90, 'counter-clockwise');
                            console.log(`ImageCompressor: Imagen ${imageFile.name} rotada 90° clockwise y dibujada.`);
                        } else {
                            // Si no necesita rotación, dibujar la imagen directamente escalada
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                            console.log(`ImageCompressor: Imagen ${imageFile.name} dibujada sin rotación.`);
                        }

                        canvas.toBlob(blob => {
                            if (blob) {
                                console.log("ImageCompressor: Compresión exitosa para", imageFile.name, "Tamaño comprimido:", blob.size);
                                resolve(blob);
                            } else {
                                console.error("ImageCompressor: Fallo al comprimir con canvas.toBlob para", imageFile.name);
                                reject(new Error("Canvas toBlob falló."));
                            }
                            // Limpiar referencias de canvas y contexto para liberar memoria
                            if (canvas) {
                                canvas.width = 0;
                                canvas.height = 0;
                            }
                        }, 'image/jpeg', quality); // Especificamos 'image/jpeg' y la calidad

                        // Limpiar referencias de la imagen cargada para liberar memoria
                        if (img) {
                            img.onload = null; // Eliminar event listener
                            img.onerror = null; // Eliminar event listener
                            img.src = ''; // Liberar la URL en memoria
                        }
                    };

                    img.onerror = () => {
                        console.error("ImageCompressor: Error al cargar imagen", imageFile.name);
                        reject(new Error("Error al cargar la imagen."));
                        // Limpiar referencias en caso de error
                        if (img) {
                            img.onload = null;
                            img.onerror = null;
                            img.src = '';
                        }
                        if (reader) {
                            reader.onload = null;
                            reader.onerror = null;
                        }
                    };
                };

                reader.onerror = () => {
                    console.error("ImageCompressor: Error FileReader al leer", imageFile.name);
                    reject(new Error("FileReader error."));
                    // Limpiar referencias en caso de error
                    if (reader) {
                        reader.onload = null;
                        reader.onerror = null;
                    }
                };
                reader.readAsDataURL(imageFile);
            });
        }
    }

    /**
     * Clase FileUploader: Responsable de subir archivos al servidor.
     */
    class FileUploader {
        /**
         * Sube datos de formulario al servidor.
         * @param {FormData} formData - Datos del formulario a subir.
         * @param {string} uploadUrl - URL del endpoint de subida.
         * @param {function} progressCallback - Callback para el progreso de subida (opcional).
         * @param {function} responseCallback - Callback para manejar la respuesta exitosa del servidor.
         * @param {function} errorCallback - Callback para manejar errores de subida.
         */
        upload(formData, uploadUrl, progressCallback, responseCallback, errorCallback) {
            console.log("FileUploader: Iniciando subida a", uploadUrl);
            fetch(uploadUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log("FileUploader: Respuesta recibida, estado:", response.status);
                    if (!response.ok) {
                        console.error("FileUploader: Error en respuesta HTTP:", response.status);
                        return response.text().then(text => {
                            throw new Error(`Error HTTP ${response.status}: ${text}`);
                        });
                    }
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else if (contentType && contentType.includes('text/html')) {
                        return response.text();
                    } else if (contentType && contentType.includes('text/plain')) {
                        return response.text();
                    } else {
                        return response.text();
                    }
                })
                .then(data => {
                    console.log("FileUploader: Subida exitosa, datos de respuesta:", data);
                    responseCallback(data);
                })
                .catch(error => {
                    console.error("FileUploader: Error durante la subida:", error);
                    errorCallback(error);
                });
        }
    }

    /**
     * Clase UIController: Responsable de gestionar la interacción con la interfaz de usuario.
     */
    class UIController {
        constructor(dropAreaId, inputFileId, previewId, progressId, progressBarSelector, uploadMessageId, formResponsesId) {
            this.dropArea = document.getElementById(dropAreaId);
            this.inputFile = document.getElementById(inputFileId);
            this.preview = document.getElementById(previewId);
            this.progress = document.getElementById(progressId);
            this.progressBar = this.progress.querySelector(progressBarSelector);
            this.uploadMessage = document.getElementById(uploadMessageId);
            this.formResponsesDiv = document.getElementById(formResponsesId);

            // Deshabilitar el botón de submit inicialmente
            this.disableSubmitButton();
        }

        initializeDropArea(handleDropCallback) {
            const dropArea = this.dropArea;
            const highlight = () => dropArea.classList.add('highlight');
            const unhighlight = () => dropArea.classList.remove('highlight');
            const preventDefaults = (e) => {
                e.preventDefault();
                e.stopPropagation();
            };

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            dropArea.addEventListener('drop', (e) => {
                unhighlight(e);
                handleDropCallback(e.dataTransfer.files);
            }, false);
        }

        initializeInputFileClick(handleClickCallback) {
            this.dropArea.addEventListener('click', () => {
                handleClickCallback();
                this.inputFile.click();
            });
        }

        initializeInputFileChange(handleChangeCallback) {
            this.inputFile.addEventListener('change', () => {
                handleChangeCallback(this.inputFile.files);
            });
        }

        // MODIFICACIÓN: previewFile ahora crea botón de eliminar
        previewFile(file, previewArea, fileName, fileIndex, deleteCallback) { // Añadido fileIndex y deleteCallback
            console.log("UIController: previewFile llamado para archivo:", fileName || 'Blob');

            // Contenedor para la miniatura, nombre del archivo e indicador de carga
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item'; // Puedes añadir estilos CSS para esto
            previewItem.dataset.index = fileIndex; // Añadir índice como data attribute

            // Imagen miniatura
            const img = document.createElement('img');
            img.classList.add('img-thumbnail', 'm-2');
            img.style.maxWidth = '100px';

            // Nombre del archivo debajo de la miniatura
            const fileNamePara = document.createElement('p');
            fileNamePara.textContent = fileName || 'Imagen';
            fileNamePara.className = 'file-name'; // Clase para estilizar si es necesario
            fileNamePara.style.wordWrap = 'break-word'; // Permite romper palabras largas
            fileNamePara.style.maxWidth = '100px';
            fileNamePara.style.fontSize = '0.8em';
            fileNamePara.style.textAlign = 'center';

            // Indicador de carga (spinner - puedes personalizarlo con CSS)
            const loadingIndicator = document.createElement('span');
            loadingIndicator.className = 'loading-indicator'; // Clase para CSS del spinner
            loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...'; // Ejemplo con FontAwesome, texto

            // MODIFICACIÓN: Botón de eliminar
            const deleteButton = document.createElement('button');
            deleteButton.innerHTML = '<i class="far fa-trash-alt"></i>';
            deleteButton.className = 'btn btn-danger btn-sm delete-btn'; // Clases Bootstrap y clase personalizada
            deleteButton.style.marginLeft = '5px'; // Espacio entre imagen y botón
            deleteButton.addEventListener('click', (event) => {
                event.preventDefault(); // Evitar que el botón dentro del formulario haga submit
                deleteCallback(fileIndex); // Llamar al callback de eliminación con el índice
            });


            previewItem.appendChild(img);
            previewItem.appendChild(fileNamePara);
            previewItem.appendChild(loadingIndicator);
            previewItem.appendChild(deleteButton); // Añadir botón de eliminar
            previewArea.appendChild(previewItem);


            let reader = new FileReader();
            reader.onloadend = () => {
                img.src = reader.result;
                loadingIndicator.style.display = 'none'; // Ocultar el indicador cuando la imagen carga
                console.log("UIController: Vista previa de imagen añadida para:", fileName || 'Blob');
                // Limpiar referencias en FileReader onloadend
                reader.onloadend = null;
                // reader = null; // Liberar referencia al FileReader
            };
            reader.readAsDataURL(file);
        }

        previewFiles(files, previewFileCallback, deleteCallback) { // MODIFICACIÓN: Añadido deleteCallback
            // MODIFICACIÓN: No limpiar preview.innerHTML aquí para permitir adición acumulativa.
            // this.preview.innerHTML = '';
            for (let i = 0; i < files.length; i++) {
                // MODIFICACIÓN: Pasar el nombre del archivo, índice y deleteCallback a previewFileCallback
                previewFileCallback(files[i], this.preview, files[i].originalName, i, deleteCallback);
            }
        }

        displayUploadProgress(percent) {
            this.progress.style.display = 'block';
            this.progressBar.style.width = percent + '%';
            this.progressBar.innerHTML = percent + '%';
        }

        hideUploadProgress() {
            this.progress.style.display = 'none';
            this.progressBar.style.width = '0%';
            this.progressBar.innerHTML = '0%';
        }

        displayUploadMessage(message, type = 'success') {
            this.uploadMessage.innerHTML += `<div class='alert alert-${type} mt-2'>${message}</div>`;
        }

        clearUploadMessages() {
            this.uploadMessage.innerHTML = '';
        }

        displayFormResponses(data) {
            this.formResponsesDiv.innerHTML = '';
            if (typeof data === 'object' && data !== null) {
                console.log("UIController: Respuesta JSON del servidor:", data);
                if (data.status === "success") {
                    let responseDiv = document.getElementById("form-responses");
                    responseDiv.innerHTML = '';
                    const alert = BootstrapAlertFactory.createAlert({
                        message: data.message || 'Ocurrió un error en la petición.',
                        type: 'success',
                        dismissible: true,
                        icon: "far fa-check-circle",
                    });
                    this.formResponsesDiv.appendChild(alert.generateAlert());
                } else if (data.status === "fail") {
                    let responseDiv = document.getElementById("form-responses");
                    responseDiv.innerHTML = '';
                    const alert = BootstrapAlertFactory.createAlert({
                        message: data.message || 'Ocurrió un error en la petición.',
                        type: 'danger',
                        dismissible: true,
                        icon: "far fa-times-circle",
                    });
                    this.formResponsesDiv.appendChild(alert.generateAlert());
                } else if (data.status === "warning") {
                    let responseDiv = document.getElementById("form-responses");
                    responseDiv.innerHTML = '';
                    const alert = BootstrapAlertFactory.createAlert({
                        message: data.message || 'Ocurrió un error en la petición.',
                        type: 'warning',
                        dismissible: true,
                        icon: "fas fa-exclamation-triangle",
                    });
                    this.formResponsesDiv.appendChild(alert.generateAlert());
                }

            } else if (typeof data === 'string') {
                this.formResponsesDiv.innerHTML = data;
            }
        }

        clearPreviewArea() {
            this.preview.innerHTML = '';
        }

        resetForm() {
            document.getElementById('image-upload-form').reset();
        }

        disableSubmitButton() {
            const botonEnviar = document.querySelector('#image-upload-form button[type="submit"]');
            if (botonEnviar) botonEnviar.disabled = true;
        }

        enableSubmitButton() {
            const botonEnviar = document.querySelector('#image-upload-form button[type="submit"]');
            if (botonEnviar) botonEnviar.disabled = false;
        }

        // MODIFICACIÓN: Método para eliminar una vista previa individualmente por índice
        removePreviewItem(index) {
            const previewItemToRemove = this.preview.querySelector(`.preview-item[data-index="${index}"]`);
            if (previewItemToRemove) {
                this.preview.removeChild(previewItemToRemove);
                // Limpiar la URL de la imagen de la vista previa para liberar recursos de imagen en el navegador
                const imgElement = previewItemToRemove.querySelector('img');
                if (imgElement) {
                    imgElement.src = ''; // Liberar la URL de la imagen
                    imgElement.removeAttribute('src'); // Remover el atributo src completamente
                    // imgElement = null; // Liberar referencia al elemento img
                }
                previewItemToRemove.innerHTML = ''; // Limpiar contenido del div
                previewItemToRemove.remove(); // Eliminar el elemento completamente
            }
        }
    }


    /**
     * Clase ImageUploadController: Orquesta la carga de imágenes, compresión y subida.
     */
    class ImageUploadController {
        constructor(compressor, uploader, uiController, uploadUrl) {
            this.compressor = compressor;
            this.uploader = uploader;
            this.uiController = uiController;
            this.uploadUrl = uploadUrl;
            this.filesToUpload = [];
            this.isUploading = false; // Nueva variable para rastrear el estado de subida
        }

        initialize() {
            this.uiController.initializeDropArea(this.handleDrop.bind(this));
            this.uiController.initializeInputFileClick(this.handleDropAreaClick.bind(this));
            this.uiController.initializeInputFileChange(this.handleInputChange.bind(this));
            document.getElementById('image-upload-form').addEventListener('submit', this.handleSubmit.bind(this));
        }


        handleDrop(files) {
            console.log("ImageUploadController: handleDrop llamado", files);
            this.processFiles(files);
        }

        handleInputChange(files) {
            console.log("ImageUploadController: handleInputChange llamado", files);
            this.processFiles(files);
        }

        handleDropAreaClick() {
            console.log("ImageUploadController: handleDropAreaClick llamado");
        }


        processFiles(files) {
            // MODIFICACIÓN: No limpiar this.filesToUpload ni preview.innerHTML para adición acumulativa
            // this.filesToUpload = [];
            // this.uiController.clearPreviewArea();

            if (!files || files.length === 0) {
                console.log("ImageUploadController: No se seleccionaron archivos.");
                if (this.filesToUpload.length === 0) {
                    this.uiController.disableSubmitButton(); // Deshabilitar si no hay archivos seleccionados
                }
                return;
            }

            const validFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
            if (validFiles.length !== files.length) {
                console.warn("ImageUploadController: Algunos archivos no son imágenes y fueron omitidos.");
                this.uiController.displayUploadMessage('Algunos archivos no son imágenes y fueron omitidos.', 'warning');
            }

            const previewFiles = []; // Array para almacenar los blobs para la vista previa.
            let fileIndex = this.filesToUpload.length; // Índice para rastrear los archivos en filesToUpload

            if (validFiles.length > 0 || this.filesToUpload.length > 0) {
                this.uiController.enableSubmitButton(); // Habilitar si hay archivos válidos o ya existen archivos
            } else {
                this.uiController.disableSubmitButton(); // Deshabilitar si no hay archivos válidos y no existen archivos previos
            }

            // Iterar sobre los archivos válidos e iniciar la compresión y la vista previa
            validFiles.forEach(file => {
                // Mostrar la vista previa inicial con indicador de carga y nombre de archivo
                // MODIFICACIÓN: Pasar el índice y el callback de eliminación a previewFile
                this.uiController.previewFile(file, this.uiController.preview, file.name, fileIndex, this.deletePreviewFile.bind(this)); // Mostrar vista previa INICIAL con indicador y botón de eliminar
                console.log("ImageUploadController: Iniciando compresión de", file.name);

                this.compressor.compress(file, {}).then(compressedBlob => {
                    if (compressedBlob) {
                        console.log("ImageUploadController: Compresión completa para", file.name);
                        // MODIFICACIÓN: Guardar el índice en filesToUpload para referenciarlo al eliminar
                        this.filesToUpload.push({
                            blob: compressedBlob,
                            originalName: file.name,
                            index: fileIndex
                        });
                        console.log("ImageUploadController: Archivo agregado a filesToUpload:", this.filesToUpload);
                        previewFiles.push(compressedBlob); // Añadir Blob comprimido para vista previa
                        // La vista previa de la imagen final se actualiza automáticamente en previewFile del UIController al cargar el blob
                    } else {
                        this.uiController.displayUploadMessage(`Error al comprimir la imagen: ${file.name}`, 'danger');
                    }
                    // Liberar la referencia al objeto File original después de la compresión
                    file = null;
                }).catch(error => {
                    this.uiController.displayUploadMessage(`Error durante la compresión de ${file.name}: ${error.message}`, 'danger');
                    // Liberar la referencia al objeto File original en caso de error
                    file = null;
                });
                fileIndex++; // Incrementar el índice para el siguiente archivo
            });
            // No necesitamos llamar a `this.uiController.previewFiles(previewFiles, this.previewFile);` aquí
            // porque ahora la vista previa se maneja individualmente dentro del bucle anterior al llamar a `this.uiController.previewFile`
            //     validFiles.forEach(file => {
            //         const currentFileIndex = this.nextFileIndex++;

            //         // Primera llamada a previewFile: muestra la vista previa inicial (puede que no esté rotada) con spinner
            //         this.uiController.previewFile(file, this.uiController.preview, file.name, currentFileIndex, this.deletePreviewFile.bind(this));

            //         // Llamada al compresor (que ahora detecta y rota)
            //         this.compressor.compress(file, {}).then(compressedBlob => {
            //             if (compressedBlob) {
            //                 // Añadir el blob comprimido a la lista de archivos a subir
            //                 this.filesToUpload.push({
            //                     blob: compressedBlob,
            //                     originalName: file.name,
            //                     index: currentFileIndex
            //                 });

            //                 // --- ESTE ES EL BLOQUE QUE ACTUALIZA LA VISTA PREVIA ---
            //                 console.log(`ImageUploadController: Compresión completa para ${file.name}, actualizando vista previa.`);
            //                 const previewItem = this.uiController.preview.querySelector(`.preview-item[data-index="${currentFileIndex}"]`);
            //                 if (previewItem) {
            //                     const previewImg = previewItem.querySelector('img');
            //                     const loadingIndicator = previewItem.querySelector('.loading-indicator');

            //                     if (previewImg) {
            //                         // Crear un URL temporal para el blob COMPRIMIDO (que ya incluye la rotación)
            //                         const blobUrl = URL.createObjectURL(compressedBlob);

            //                         previewImg.onload = () => {
            //                             URL.revokeObjectURL(blobUrl); // Liberar la URL temporal una vez cargada
            //                             if (loadingIndicator) loadingIndicator.style.display = 'none'; // Ocultar spinner
            //                             console.log(`UIController: Vista previa de ${file.name} actualizada con el Blob comprimido/rotado.`);
            //                             previewImg.onload = null; // Limpiar listeners
            //                             previewImg.onerror = null;
            //                         };
            //                         previewImg.onerror = () => {
            //                             URL.revokeObjectURL(blobUrl);
            //                             if (loadingIndicator) loadingIndicator.style.display = 'none';
            //                             console.error(`UIController: Error cargando el blob comprimido/rotado en la vista previa para ${file.name}.`);
            //                             previewImg.onload = null;
            //                             previewImg.onerror = null;
            //                         };
            //                         previewImg.src = blobUrl; // Establecer la fuente de la imagen de vista previa al Blob URL
            //                     } else {
            //                         console.warn("ImageUploadController: No se encontró el elemento img para actualizar la vista previa con índice:", currentFileIndex);
            //                         if (loadingIndicator) loadingIndicator.style.display = 'none';
            //                     }
            //                 } else {
            //                     console.warn("ImageUploadController: No se encontró el elemento de vista previa con índice", currentFileIndex, "para actualizar.");
            //                 }
            //                 // ---------------------------------------------------------

            //             } else {
            //                 // Si la compresión falló completamente para este archivo
            //                 console.error(`ImageUploadController: Compresión fallida para ${file.name}.`);
            //                 this.uiController.displayUploadMessage(`Error al comprimir la imagen: ${file.name}`, 'danger');
            //                 // Remover la vista previa inicial si la compresión falló
            //                 this.uiController.removePreviewItem(currentFileIndex);
            //             }
            //             // ... (limpieza de la referencia al archivo original) ...
            //         }).catch(error => {
            //             // Si hubo un error durante la compresión (catch)
            //             console.error(`ImageUploadController: Error durante la compresión de ${file.name}:`, error);
            //             this.uiController.displayUploadMessage(`Error durante la compresión de ${file.name}: ${error.message}`, 'danger');
            //             // Remover la vista previa inicial si hubo un error
            //             this.uiController.removePreviewItem(currentFileIndex);
            //             // ... (limpieza de la referencia al archivo original) ...
            //         });
            //     });
            // Versión corregida del código para manejar vista previa de imágenes rotadas
            // Versión corregida del código para manejar vista previa de imágenes rotadas
            // validFiles.forEach(file => {
            //     const currentFileIndex = this.nextFileIndex++;

            //     // Primera llamada a previewFile: muestra la vista previa inicial con spinner
            //     this.uiController.previewFile(file, this.uiController.preview, file.name, currentFileIndex, this.deletePreviewFile.bind(this));

            //     // Llamada al compresor (que ahora detecta y rota)
            //     this.compressor.compress(file, {}).then(compressedBlob => {
            //         if (compressedBlob) {
            //             // Añadir el blob comprimido a la lista de archivos a subir
            //             this.filesToUpload.push({
            //                 blob: compressedBlob,
            //                 originalName: file.name,
            //                 index: currentFileIndex
            //             });

            //             // Actualizar la vista previa con la imagen rotada/comprimida
            //             console.log(`ImageUploadController: Compresión completa para ${file.name}, actualizando vista previa.`);
            //             const previewItem = this.uiController.preview.querySelector(`.preview-item[data-index="${currentFileIndex}"]`);

            //             if (previewItem) {
            //                 const previewImg = previewItem.querySelector('img');
            //                 const loadingIndicator = previewItem.querySelector('.loading-indicator');

            //                 if (previewImg) {
            //                     // Crear un URL temporal para el blob COMPRIMIDO (que ya incluye la rotación)
            //                     const blobUrl = URL.createObjectURL(compressedBlob);

            //                     previewImg.onload = () => {
            //                         // Liberar la URL temporal una vez cargada
            //                         URL.revokeObjectURL(blobUrl);
            //                         // Ocultar spinner
            //                         if (loadingIndicator) loadingIndicator.style.display = 'none';
            //                         console.log(`UIController: Vista previa de ${file.name} actualizada con el Blob comprimido/rotado.`);
            //                         // Limpiar listeners
            //                         previewImg.onload = null;
            //                         previewImg.onerror = null;
            //                     };

            //                     previewImg.onerror = () => {
            //                         URL.revokeObjectURL(blobUrl);
            //                         if (loadingIndicator) loadingIndicator.style.display = 'none';
            //                         console.error(`UIController: Error cargando el blob comprimido/rotado en la vista previa para ${file.name}.`);
            //                         previewImg.onload = null;
            //                         previewImg.onerror = null;
            //                     };

            //                     // Establecer la fuente de la imagen de vista previa al Blob URL
            //                     previewImg.src = blobUrl;
            //                 } else {
            //                     console.warn("ImageUploadController: No se encontró el elemento img para actualizar la vista previa con índice:", currentFileIndex);
            //                     if (loadingIndicator) loadingIndicator.style.display = 'none';
            //                 }
            //             } else {
            //                 console.warn("ImageUploadController: No se encontró el elemento de vista previa con índice", currentFileIndex, "para actualizar.");
            //             }
            //         } else {
            //             // Si la compresión falló completamente para este archivo
            //             console.error(`ImageUploadController: Compresión fallida para ${file.name}.`);
            //             this.uiController.displayUploadMessage(`Error al comprimir la imagen: ${file.name}`, 'danger');
            //             // Remover la vista previa inicial si la compresión falló
            //             this.uiController.removePreviewItem(currentFileIndex);
            //         }

            //         // Liberar la referencia al objeto File original después de la compresión
            //         file = null;
            //     }).catch(error => {
            //         // Si hubo un error durante la compresión (catch)
            //         console.error(`ImageUploadController: Error durante la compresión de ${file.name}:`, error);
            //         this.uiController.displayUploadMessage(`Error durante la compresión de ${file.name}: ${error.message}`, 'danger');
            //         // Remover la vista previa inicial si hubo un error
            //         this.uiController.removePreviewItem(currentFileIndex);
            //         // Liberar la referencia al objeto File original en caso de error
            //         file = null;
            //     });
            // });
        }


        previewFile(file, previewArea) {
            // Esta función previewFile en ImageUploadController ya no es directamente utilizada
            // La lógica de vista previa ahora se delega completamente a UIController.previewFile
            // y se llama individualmente en processFiles para cada archivo.
        }

        // MODIFICACIÓN: Función para eliminar archivo de la vista previa y filesToUpload
        deletePreviewFile(indexToDelete) {
            console.log("ImageUploadController: deletePreviewFile llamado con índice:", indexToDelete);
            // 1. Eliminar el elemento de vista previa del DOM usando UIController
            this.uiController.removePreviewItem(indexToDelete);

            // 2. Filtrar filesToUpload para remover el archivo con el índice correspondiente
            this.filesToUpload = this.filesToUpload.filter(fileData => fileData.index !== indexToDelete);

            console.log("ImageUploadController: Archivo con índice", indexToDelete, "eliminado.");

            if (this.filesToUpload.length === 0) {
                this.uiController.disableSubmitButton(); // Deshabilitar si no quedan archivos
            } else {
                this.uiController.enableSubmitButton(); // Habilitar si aún quedan archivos
            }
        }


        handleSubmit(e) {
            e.preventDefault();
            this.uploadFormData();
        }


        uploadFormData() {
            console.log("ImageUploadController: uploadFormData llamado");
            this.uiController.clearUploadMessages();
            if (!this.filesToUpload || this.filesToUpload.length === 0) {
                this.uiController.displayUploadMessage('No hay imágenes seleccionadas para subir.', 'warning');
                this.uiController.disableSubmitButton(); // Asegurar que el botón esté deshabilitado si no hay imágenes
                return;
            }

            if (this.isUploading) {
                console.log("Subida ya en progreso, abortando nueva subida.");
                return; // Evitar múltiples subidas simultáneas
            }
            this.isUploading = true;
            this.uiController.disableSubmitButton(); // Deshabilitar botón al iniciar subida


            const formData = new FormData(document.getElementById('image-upload-form'));
            for (let i = 0; i < this.filesToUpload.length; i++) {
                const fileData = this.filesToUpload[i];
                const compressedFile = new File([fileData.blob], fileData.originalName, {
                    type: 'image/jpeg'
                });
                formData.append('images[]', compressedFile);
                // Liberar referencia al blob después de crear el File y añadirlo a formData
                fileData.blob = null;
            }

            this.uiController.displayUploadProgress(0);


            const uploadUrl = this.uploadUrl; // Asegúrate de que uploadUrl esté disponible aquí
            const progressCallback = (event) => {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    this.uiController.displayUploadProgress(percentComplete);
                }
            };

            const responseCallback = (data) => {
                this.uiController.hideUploadProgress();
                this.uiController.enableSubmitButton(); // Re-habilitar botón tras éxito
                // this.uiController.displayUploadMessage('Formulario e imágenes subidos correctamente.', 'success');
                this.uiController.displayFormResponses(data);
                this.uiController.clearPreviewArea();
                this.uiController.resetForm();
                this.filesToUpload = [];
                this.isUploading = false; // Resetear flag de subida
                if (this.filesToUpload.length === 0) {
                    this.uiController.disableSubmitButton(); // Deshabilitar si ya no hay archivos
                }
            };

            const errorCallback = (error) => {
                this.uiController.hideUploadProgress();
                this.uiController.enableSubmitButton(); // Re-habilitar botón tras error
                this.uiController.displayUploadMessage(`Error en la subida: ${error.message || 'Error desconocido.'}`, 'danger');
                this.uiController.displayFormResponses({
                    status: 'error',
                    message: error.message || 'Error de subida.'
                });
                this.isUploading = false; // Resetear flag de subida
            };


            this.uploader.upload(formData, uploadUrl, progressCallback, responseCallback, errorCallback);
            this.uiController.displayUploadProgress(100); // Simular progreso completo al iniciar
            this.uiController.progressBar.innerHTML = 'Subiendo...'; // Cambiar texto para indicar subida
        }
    }


    document.addEventListener('DOMContentLoaded', function() {

        // Asegúrate de que 'AjaxHandler' y 'elemento' con 'data-valor-base-64' están definidos en tu contexto.
        // Este es un ejemplo y necesitarás adaptarlo a tu configuración específica.
        const elemento = document.getElementById('trash'); // Reemplaza 'trash' con el ID de tu elemento si es diferente
        const valorBase64 = elemento.getAttribute('data-valor-base64');
        // Asumiendo que AjaxHandler.decodificarBase64 es una función global accesible
        const url = typeof AjaxHandler !== 'undefined' && AjaxHandler.decodificarBase64 ? AjaxHandler.decodificarBase64(valorBase64) : '/api'; // Usar '/api' como fallback si AjaxHandler no está definido
        console.log("URL base decodificada:", url);



        const calidad = 0.7;
        const maxWidth = 1000;
        // Instanciar componentes
        const imageCompressor = new ImageCompressor(calidad, maxWidth); // Configuración de compresión: calidad 0.6, maxWidth 600
        const fileUploader = new FileUploader();
        const uiController = new UIController(
            'drop-area',
            'images',
            'image-preview',
            'upload-progress',
            '.progress-bar',
            'upload-message',
            'form-responses'
        );
        const imageUploadController = new ImageUploadController(imageCompressor, fileUploader, uiController, url + '/eventos/clientes/subir-imagenes');

        // Inicializar el controlador principal
        imageUploadController.initialize();
    });
</script>