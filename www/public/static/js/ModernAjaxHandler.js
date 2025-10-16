// class ModernAjaxHandler {
//   constructor(config = {}) {
//     this.baseURL = config.baseURL || ""; // URL base opcional
//     this.headers = config.headers || {}; // Encabezados por defecto
//     this.timeout = config.timeout || 0; // Timeout por defecto en milisegundos (0 significa sin timeout)
//   }

//   async request(method, url, data = null, config = {}) {
//     try {
//       const fullURL = this.baseURL + url;
//       const xhr = new XMLHttpRequest();

//       return new Promise((resolve, reject) => {
//         xhr.open(method, fullURL);

//         // Configurar encabezados
//         const requestHeaders = { ...this.headers, ...config.headers };
//         for (const key in requestHeaders) {
//           xhr.setRequestHeader(key, requestHeaders[key]);
//         }

//         // Configurar el tipo de respuesta
//         if (config.responseType) {
//           xhr.responseType = config.responseType;
//         }

//         // Configurar el timeout
//         if (config.timeout > 0) {
//           xhr.timeout = config.timeout;
//         } else if (this.timeout > 0) {
//           xhr.timeout = this.timeout;
//         }

//         xhr.onload = () => {
//           if (xhr.status >= 200 && xhr.status < 300) {
//             resolve(xhr.response);
//           } else {
//             reject({
//               status: xhr.status,
//               statusText: xhr.statusText,
//               response: xhr.response,
//               message: `Error en la petición. Código de estado: ${xhr.status}`,
//             });
//           }
//         };

//         xhr.onerror = () => {
//           reject({
//             message: "Error de red al realizar la petición.",
//           });
//         };

//         xhr.ontimeout = () => {
//           reject({
//             message: `La petición ha excedido el tiempo de espera configurado (${xhr.timeout} ms).`,
//           });
//         };

//         // Enviar los datos
//         if (data) {
//           if (data instanceof FormData) {
//             xhr.send(data); // Enviar el FormData directamente
//           } else if (
//             typeof data === "object" &&
//             requestHeaders["Content-Type"] === "application/json"
//           ) {
//             xhr.send(JSON.stringify(data));
//           } else {
//             xhr.send(data); // Enviar como está (string, etc.)
//           }
//         } else {
//           xhr.send();
//         }
//       });
//     } catch (error) {
//       console.error("Error inesperado en la petición:", error);
//       return Promise.reject({
//         message: "Error inesperado al procesar la petición.",
//       });
//     }
//   }

//   // async request(method, url, data = null, config = {}) {
//   //   try {
//   //     const fullURL = this.baseURL + url;
//   //     const xhr = new XMLHttpRequest();

//   //     return new Promise((resolve, reject) => {
//   //       xhr.open(method, fullURL);

//   //       // Configurar encabezados
//   //       const requestHeaders = { ...this.headers, ...config.headers };
//   //       for (const key in requestHeaders) {
//   //         xhr.setRequestHeader(key, requestHeaders[key]);
//   //       }

//   //       // Configurar el tipo de respuesta
//   //       if (config.responseType) {
//   //         xhr.responseType = config.responseType;
//   //       }

//   //       // Configurar el timeout
//   //       if (config.timeout > 0) {
//   //         xhr.timeout = config.timeout;
//   //       } else if (this.timeout > 0) {
//   //         xhr.timeout = this.timeout;
//   //       }

//   //       xhr.onload = () => {
//   //         if (xhr.status >= 200 && xhr.status < 300) {
//   //           resolve(xhr.response);
//   //         } else {
//   //           reject({
//   //             status: xhr.status,
//   //             statusText: xhr.statusText,
//   //             response: xhr.response,
//   //             message: `Error en la petición. Código de estado: ${xhr.status}`,
//   //           });
//   //         }
//   //       };

//   //       xhr.onerror = () => {
//   //         reject({
//   //           message: "Error de red al realizar la petición.",
//   //         });
//   //       };

//   //       xhr.ontimeout = () => {
//   //         reject({
//   //           message: `La petición ha excedido el tiempo de espera configurado (${xhr.timeout} ms).`,
//   //         });
//   //       };

//   //       // Enviar los datos
//   //       if (data) {
//   //         if (
//   //           typeof data === "object" &&
//   //           requestHeaders["Content-Type"] !== "application/json"
//   //         ) {
//   //           // Si los datos son un objeto y no se especifica JSON, se asume que se deben enviar como FormData
//   //           const formData = new FormData();
//   //           for (const key in data) {
//   //             formData.append(key, data[key]);
//   //           }
//   //           xhr.send(formData);
//   //         } else if (
//   //           typeof data === "object" &&
//   //           requestHeaders["Content-Type"] === "application/json"
//   //         ) {
//   //           xhr.send(JSON.stringify(data));
//   //         } else {
//   //           xhr.send(data); // Enviar como está (string, etc.)
//   //         }
//   //       } else {
//   //         xhr.send();
//   //       }
//   //     });
//   //   } catch (error) {
//   //     console.error("Error inesperado en la petición:", error);
//   //     return Promise.reject({
//   //       message: "Error inesperado al procesar la petición.",
//   //     });
//   //   }
//   // }

//   async get(url, config = {}) {
//     return this.request("GET", url, null, config);
//   }

//   async post(url, data = null, config = {}) {
//     return this.request("POST", url, data, {
//       headers: {
//         "Content-Type": "application/json", // Encabezado por defecto para POST
//         ...config.headers,
//       },
//       ...config,
//     });
//   }

//   async put(url, data = null, config = {}) {
//     return this.request("PUT", url, data, {
//       headers: {
//         "Content-Type": "application/json", // Encabezado por defecto para PUT
//         ...config.headers,
//       },
//       ...config,
//     });
//   }

//   async delete(url, config = {}) {
//     return this.request("DELETE", url, null, config);
//   }
// }
class ModernAjaxHandler {
  constructor(config = {}) {
    this.baseURL = config.baseURL || ""; // URL base opcional
    this.headers = config.headers || {}; // Encabezados por defecto
    this.timeout = config.timeout || 0; // Timeout por defecto en milisegundos (0 significa sin timeout)
  }

  async request(method, url, data = null, config = {}) {
    try {
      const fullURL = this.baseURL + url;
      const xhr = new XMLHttpRequest();

      return new Promise((resolve, reject) => {
        xhr.open(method, fullURL);

        // Configurar encabezados
        const requestHeaders = { ...this.headers, ...config.headers };
        for (const key in requestHeaders) {
          xhr.setRequestHeader(key, requestHeaders[key]);
        }

        // NO establecer responseType para permitir detección automática
        // Solo establecerlo si se especifica explícitamente
        if (config.responseType) {
          xhr.responseType = config.responseType;
        }

        // Configurar el timeout
        if (config.timeout > 0) {
          xhr.timeout = config.timeout;
        } else if (this.timeout > 0) {
          xhr.timeout = this.timeout;
        }

        xhr.onload = () => {
          if (xhr.status >= 200 && xhr.status < 300) {
            let response = xhr.response || xhr.responseText;

            // Detectar el tipo de contenido de la respuesta
            const contentType = xhr.getResponseHeader("Content-Type") || "";

            // Si es JSON, intentar parsearlo
            if (
              contentType.includes("application/json") ||
              (typeof response === "string" && this.looksLikeJson(response))
            ) {
              try {
                response = JSON.parse(response);
              } catch (e) {
                // Si falla el parseo, mantener la respuesta original
                console.warn("No se pudo parsear la respuesta como JSON:", e);
              }
            }

            // Si es HTML y necesitamos procesarlo como DOM, podríamos agregarlo aquí
            // Pero por ahora, simplemente devolvemos el texto HTML

            resolve(response);
          } else {
            reject({
              status: xhr.status,
              statusText: xhr.statusText,
              response: xhr.response || xhr.responseText,
              message: `Error en la petición. Código de estado: ${xhr.status}`,
            });
          }
        };

        xhr.onerror = () => {
          reject({
            message: "Error de red al realizar la petición.",
          });
        };

        xhr.ontimeout = () => {
          reject({
            message: `La petición ha excedido el tiempo de espera configurado (${xhr.timeout} ms).`,
          });
        };

        // Enviar los datos
        if (data) {
          if (data instanceof FormData) {
            xhr.send(data); // Enviar el FormData directamente
          } else if (
            typeof data === "object" &&
            !requestHeaders["Content-Type"] &&
            !(data instanceof Blob) &&
            !(data instanceof ArrayBuffer)
          ) {
            // Si es un objeto y no se ha especificado Content-Type, usar JSON por defecto
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(JSON.stringify(data));
          } else if (
            typeof data === "object" &&
            requestHeaders["Content-Type"] === "application/json"
          ) {
            xhr.send(JSON.stringify(data));
          } else {
            xhr.send(data); // Enviar como está (string, etc.)
          }
        } else {
          xhr.send();
        }
      });
    } catch (error) {
      console.error("Error inesperado en la petición:", error);
      return Promise.reject({
        message: "Error inesperado al procesar la petición.",
      });
    }
  }

  // Método para verificar si una cadena parece ser JSON
  looksLikeJson(str) {
    if (typeof str !== "string") return false;
    str = str.trim();
    return (
      (str.startsWith("{") && str.endsWith("}")) ||
      (str.startsWith("[") && str.endsWith("]"))
    );
  }

  async get(url, config = {}) {
    return this.request("GET", url, null, config);
  }

  async post(url, data = null, config = {}) {
    return this.request("POST", url, data, {
      headers: {
        "Content-Type": "application/json", // Encabezado por defecto para POST
        ...config.headers,
      },
      ...config,
    });
  }

  async put(url, data = null, config = {}) {
    return this.request("PUT", url, data, {
      headers: {
        "Content-Type": "application/json", // Encabezado por defecto para PUT
        ...config.headers,
      },
      ...config,
    });
  }

  async delete(url, config = {}) {
    return this.request("DELETE", url, null, config);
  }

  // Método para enviar formularios FormData
  async postForm(url, formData, config = {}) {
    // No se especifica Content-Type para que el navegador establezca el límite multipart/form-data automáticamente
    return this.request("POST", url, formData, config);
  }

  // Método para solicitar específicamente HTML
  async getHtml(url, config = {}) {
    return this.request("GET", url, null, {
      ...config,
      headers: {
        Accept: "text/html",
        ...config.headers,
      },
    });
  }

  // Método para solicitar específicamente texto plano
  async getText(url, config = {}) {
    return this.request("GET", url, null, {
      ...config,
      headers: {
        Accept: "text/plain",
        ...config.headers,
      },
      responseType: "text",
    });
  }

  // Método para solicitar específicamente JSON
  async getJson(url, config = {}) {
    return this.request("GET", url, null, {
      ...config,
      headers: {
        Accept: "application/json",
        ...config.headers,
      },
    });
  }
}
