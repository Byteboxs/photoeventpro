class Base64Decoder {
  constructor(elementId) {
    this.element = document.getElementById(elementId);
    this.base64String = this.element
      ? this.element.getAttribute("data-valor-base64")
      : null;
  }

  decode() {
    if (!this.base64String) {
      console.warn("El atributo data-valor-base64 no está presente o es nulo.");
      return null;
    }

    try {
      return atob(this.base64String);
    } catch (error) {
      console.error("Error al decodificar Base64:", error);
      return null;
    }
  }
}
