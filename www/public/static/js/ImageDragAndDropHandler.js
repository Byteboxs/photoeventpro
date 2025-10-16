// /**
//  * Image Drag and Drop Handler Class
//  *
//  * Handles drag and drop functionality for images, including highlighting the drop area,
//  * processing dropped files, and displaying image previews.
//  * Allows configuration for single or multiple image uploads.
//  */
// class ImageDragAndDropHandler {
//   /**
//    * Constructor for ImageDragAndDropHandler.
//    * @param {string} dropAreaId - ID of the drop area element.
//    * @param {string} fileInputId - ID of the file input element.
//    * @param {string} imagePreviewId - ID of the image preview area element.
//    * @param {boolean} [multiple=true] - Optional. If true, allows multiple images. If false, allows only single image. Defaults to true.
//    */
//   constructor(dropAreaId, fileInputId, imagePreviewId, multiple = true) {
//     /** @private @type {HTMLElement} */
//     this.dropArea = document.getElementById(dropAreaId);
//     /** @private @type {HTMLInputElement} */
//     this.fileInput = document.getElementById(fileInputId);
//     /** @private @type {HTMLElement} */
//     this.imagePreview = document.getElementById(imagePreviewId);
//     /** @private @type {boolean} */
//     this.multiple = multiple; // Store the multiple upload configuration

//     this.setupEventListeners();
//   }

//   /**
//    * Sets up all necessary event listeners for drag and drop functionality.
//    * @private
//    */
//   setupEventListeners() {
//     // Prevent default drag behaviors on document to prevent unwanted browser actions
//     ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
//       document.addEventListener(eventName, this.preventDefaults, false);
//     });

//     ["dragenter", "dragover"].forEach((eventName) => {
//       this.dropArea.addEventListener(
//         eventName,
//         this.highlightDropArea.bind(this),
//         false
//       );
//     });

//     ["dragleave", "drop"].forEach((eventName) => {
//       this.dropArea.addEventListener(
//         eventName,
//         this.unhighlightDropArea.bind(this),
//         false
//       );
//     });

//     this.dropArea.addEventListener("drop", this.handleDrop.bind(this), false);
//     this.dropArea.addEventListener(
//       "click",
//       this.triggerFileInput.bind(this),
//       false
//     ); // Click to open file input
//     this.fileInput.addEventListener(
//       "change",
//       this.handleFileInputChange.bind(this),
//       false
//     ); // Handle file input change
//   }

//   /**
//    * Prevents default browser behaviors for drag and drop events.
//    * @private
//    * @param {DragEvent} event - The drag event.
//    */
//   preventDefaults(event) {
//     event.preventDefault();
//     event.stopPropagation();
//   }

//   /**
//    * Highlights the drop area by adding a 'highlight' class.
//    * @private
//    * @param {DragEvent} event - The drag event.
//    */
//   highlightDropArea(event) {
//     this.dropArea.classList.add("highlight");
//   }

//   /**
//    * Unhighlights the drop area by removing the 'highlight' class.
//    * @private
//    * @param {DragEvent} event - The drag event.
//    */
//   unhighlightDropArea(event) {
//     this.dropArea.classList.remove("highlight");
//   }

//   /**
//    * Handles the drop event, processes the dropped files.
//    * @private
//    * @param {DragEvent} event - The drag event.
//    */
//   handleDrop(event) {
//     const files = event.dataTransfer.files;
//     this.handleFiles(files);
//   }

//   /**
//    * Triggers the file input element to open the file selection dialog.
//    * @private
//    */
//   triggerFileInput() {
//     this.fileInput.click();
//   }

//   /**
//    * Handles the change event of the file input, processes selected files.
//    * @private
//    */
//   handleFileInputChange() {
//     const files = this.fileInput.files;
//     this.handleFiles(files);
//   }

//   /**
//    * Handles a FileList, reads each file and updates the image preview.
//    * @private
//    * @param {FileList} files - List of files to handle.
//    */
//   handleFiles(files) {
//     files = [...files]; // Convert FileList to Array

//     if (!this.multiple && files.length > 0) {
//       // Single image mode: clear previous previews and only show the first (or last, in this case of loop)
//       this.imagePreview.innerHTML = ""; // Clear previous previews
//       // We only process the last file in single mode for better UX in case of multiple file selection
//       this.previewFile(files[files.length - 1]);
//     } else {
//       // Multiple images mode: append new previews
//       files.forEach(this.previewFile.bind(this));
//     }
//   }

//   /**
//    * Previews a single file by reading it as a data URL and displaying it in the preview area.
//    * @private
//    * @param {File} file - The file to preview.
//    */
//   previewFile(file) {
//     if (file.type.startsWith("image/")) {
//       // Check if the file is an image
//       const reader = new FileReader();
//       reader.onloadend = () => {
//         const img = document.createElement("img");
//         img.src = reader.result;
//         img.style.maxWidth = "100%"; // Optional: Style adjustments
//         img.style.height = "auto"; // Optional: Style adjustments
//         this.imagePreview.appendChild(img);
//       };
//       reader.readAsDataURL(file);
//     } else {
//       alert("Por favor, arrastra y suelta solo archivos de imagen."); // Notify user for non-image files
//     }
//   }
// }

/**
 * Image Drag and Drop Handler Class
 *
 * Handles drag and drop functionality for images, including highlighting the drop area,
 * processing dropped files, and displaying image previews.
 * Allows configuration for single or multiple image uploads.
 */
class ImageDragAndDropHandler {
  /**
   * Constructor for ImageDragAndDropHandler.
   * @param {string} dropAreaId - ID of the drop area element.
   * @param {string} fileInputId - ID of the file input element.
   * @param {string} imagePreviewId - ID of the image preview area element.
   * @param {boolean} [multiple=true] - Optional. If true, allows multiple images. If false, allows only single image. Defaults to true.
   */
  constructor(dropAreaId, fileInputId, imagePreviewId, multiple = true) {
    /** @private @type {HTMLElement} */
    this.dropArea = document.getElementById(dropAreaId);
    /** @private @type {HTMLInputElement} */
    this.fileInput = document.getElementById(fileInputId);
    /** @private @type {HTMLElement} */
    this.imagePreview = document.getElementById(imagePreviewId);
    /** @private @type {boolean} */
    this.multiple = multiple; // Store the multiple upload configuration

    this.setupEventListeners();
  }

  /**
   * Sets up all necessary event listeners for drag and drop functionality.
   * @private
   */
  setupEventListeners() {
    // Prevent default drag behaviors on document to prevent unwanted browser actions
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
    ); // Click to open file input
    this.fileInput.addEventListener(
      "change",
      this.handleFileInputChange.bind(this),
      false
    ); // Handle file input change
  }

  /**
   * Prevents default browser behaviors for drag and drop events.
   * @private
   * @param {DragEvent} event - The drag event.
   */
  preventDefaults(event) {
    event.preventDefault();
    event.stopPropagation();
  }

  /**
   * Highlights the drop area by adding a 'highlight' class.
   * @private
   * @param {DragEvent} event - The drag event.
   */
  highlightDropArea(event) {
    this.dropArea.classList.add("highlight");
  }

  /**
   * Unhighlights the drop area by removing the 'highlight' class.
   * @private
   * @param {DragEvent} event - The drag event.
   */
  unhighlightDropArea(event) {
    this.dropArea.classList.remove("highlight");
  }

  /**
   * Handles the drop event, processes the dropped files.
   * @private
   * @param {DragEvent} event - The drag event.
   */
  handleDrop(event) {
    const files = event.dataTransfer.files;
    this.handleFiles(files);
    // **FIX: Set the files to the hidden file input**
    this.fileInput.files = files; // Assign the FileList to the hidden input
  }

  /**
   * Triggers the file input element to open the file selection dialog.
   * @private
   */
  triggerFileInput() {
    this.fileInput.click();
  }

  /**
   * Handles the change event of the file input, processes selected files.
   * @private
   */
  handleFileInputChange() {
    const files = this.fileInput.files;
    this.handleFiles(files);
    // **FIX: Set the files to the hidden file input**
    this.fileInput.files = files; // Assign the FileList to the hidden input
  }

  /**
   * Handles a FileList, reads each file and updates the image preview.
   * @private
   * @param {FileList} files - List of files to handle.
   */
  handleFiles(files) {
    files = [...files]; // Convert FileList to Array

    if (!this.multiple && files.length > 0) {
      // Single image mode: clear previous previews and only show the first (or last, in this case of loop)
      this.imagePreview.innerHTML = ""; // Clear previous previews
      // We only process the last file in single mode for better UX in case of multiple file selection
      this.previewFile(files[files.length - 1]);
    } else {
      // Multiple images mode: append new previews
      files.forEach(this.previewFile.bind(this));
    }
  }

  /**
   * Previews a single file by reading it as a data URL and displaying it in the preview area.
   * @private
   * @param {File} file - The file to preview.
   */
  previewFile(file) {
    if (file.type.startsWith("image/")) {
      // Check if the file is an image
      const reader = new FileReader();
      reader.onloadend = () => {
        const img = document.createElement("img");
        img.src = reader.result;
        img.style.maxWidth = "100%"; // Optional: Style adjustments
        img.style.height = "auto"; // Optional: Style adjustments
        this.imagePreview.appendChild(img);
      };
      reader.readAsDataURL(file);
    } else {
      alert("Por favor, arrastra y suelta solo archivos de imagen."); // Notify user for non-image files
    }
  }
}
