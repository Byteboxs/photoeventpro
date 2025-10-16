class AlbumManager {
  constructor(pages) {
    if (AlbumManager.instance) {
      return AlbumManager.instance;
    }
    AlbumManager.instance = this;
    this.maxPages = pages;

    this.album = [];
    this.initializeAlbumSheets();
    this.bindEvents();
  }

  initializeAlbumSheets() {
    const albumContainer = document.getElementById("albumContainer");
    for (let i = 0; i < this.maxPages; i++) {
      const sheet = this.createAlbumSheet(i + 1);
      albumContainer.appendChild(sheet);
    }
  }

  createAlbumSheet(sheetNumber) {
    const sheet = document.createElement("div");
    sheet.classList.add("album-sheet");
    sheet.setAttribute("data-sheet-id", sheetNumber);

    const leftPage = this.createAlbumPage(sheetNumber, 1);
    const rightPage = this.createAlbumPage(sheetNumber, 2);

    sheet.appendChild(leftPage);
    sheet.appendChild(rightPage);

    return sheet;
  }

  createAlbumPage(sheetNumber, pageNumber) {
    const page = document.createElement("div");
    const consecutivePhotoNumber = (sheetNumber - 1) * 2 + pageNumber;
    page.classList.add("album-page");
    page.setAttribute("data-sheet-id", sheetNumber);
    page.setAttribute("data-page-number", pageNumber);

    const pageLabel = document.createElement("div");
    pageLabel.classList.add("album-page-label");
    pageLabel.textContent = `Hoja ${sheetNumber} - Foto ${consecutivePhotoNumber}`;
    page.appendChild(pageLabel);

    page.addEventListener("dragover", this.handleDragOver);
    page.addEventListener("drop", this.handleDrop);

    return page;
  }

  bindEvents() {
    document
      .getElementById("saveAlbumBtn")
      .addEventListener("click", this.saveAlbum.bind(this));
    // document.getElementById('printAlbumBtn').addEventListener('click', this.printAlbum.bind(this));
    document
      .getElementById("resetAlbumBtn")
      .addEventListener("click", this.resetAlbum.bind(this));
  }

  handleDragOver(event) {
    event.preventDefault();
    event.currentTarget.classList.add("drag-over");
  }

  handleDrop(event) {
    event.preventDefault();
    const imageId = event.dataTransfer.getData("text/plain");
    const image = document.getElementById(imageId);
    const page = event.currentTarget;

    // Limpiar página anterior si ya tiene imagen
    const existingImage = page.querySelector("img");

    if (image.classList.contains("preloaded-image")) {
      // Si es una imagen del panel, eliminarla del panel
      image.remove();
    } else if (image.classList.contains("album-image")) {
      // Si es una imagen del álbum, moverla
      image.parentElement.removeChild(image);
    }

    if (existingImage) {
      existingImage.remove();
    }

    // Clonar o mover imagen
    const processedImage = image.classList.contains("preloaded-image")
      ? image.cloneNode(true)
      : image;

    processedImage.classList.remove("preloaded-image", "dragging");
    processedImage.classList.add("album-image");
    page.appendChild(processedImage);
    page.classList.remove("drag-over");
  }

  saveAlbum() {
    const albumData = Array.from(document.querySelectorAll(".album-page")).map(
      (page) => ({
        pageId: page.getAttribute("data-page-id"),
        imageUrl: page.querySelector("img")?.src || null,
      })
    );

    console.log(JSON.stringify(albumData));
    // Aquí iría la lógica de guardar en base de datos
  }

  printAlbum() {
    window.print();
  }

  resetAlbum() {
    const albumPages = document.querySelectorAll(".album-page");
    const imagePanel = document.getElementById("preloadedImages");

    // Restaurar imágenes al panel
    albumPages.forEach((page) => {
      const image = page.querySelector("img");
      if (image && image.classList.contains("album-image")) {
        const originalImage = image.cloneNode(true);
        originalImage.classList.remove("album-image");
        originalImage.classList.add("preloaded-image");
        imagePanel.appendChild(originalImage);
        image.remove();
      }
    });

    // Añadir eventos de drag para las imágenes restauradas
    const newImages = imagePanel.querySelectorAll("img");
    newImages.forEach((img) => {
      img.draggable = true;
      img.addEventListener("dragstart", ImageFactory.handleDragStart);
    });
  }
}
