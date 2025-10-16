# Guía para Agentes de IA: Uso de PhotoEventPro

Este documento detalla la estructura y los puntos clave del código para facilitar la interacción de modelos de lenguaje grandes (LLMs) con el proyecto.

## 1. Estructura de la Aplicación (Carpeta `www/`)

- **Controladores (`www/controllers`)**: Contiene la lógica de negocio y manejo de peticiones HTTP.
- **Modelos (`www/models`)**: Maneja la interacción con la base de datos (DB).
- **Configuración (Ignorada)**: El archivo `www/cfg.php` contiene credenciales sensibles y está ignorado. La plantilla de ejemplo es `www/cfg.example.php`.

## 2. Esquema de la Base de Datos

La estructura completa de las tablas SQL del proyecto se encuentra en el archivo:

- **Esquema DB:** `context/database.sql`

## 3. Puntos Críticos de Interacción

1.  **Archivos Subidos**: Los archivos (imágenes, etc.) se almacenan en la carpeta **`www/uploads/`**, que está ignorada por Git.
2.  **Logs**: La carpeta **`www/log/`** contiene registros de errores y también está ignorada.
