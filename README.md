# PhotoEventPro
Aplicación web para gestionar eventos fotográficos, coordinando proyectos, vendedores y servicios dentro de una infraestructura basada en contenedores.

## Tecnologías
- PHP
- MySQL
- Docker
- Ubuntu 24

## Configuración Local (Setup)
1. Clona el repositorio: `git clone <url-del-repo>`.
2. Copia `www/cfg.example.php` a `www/cfg.php` y actualiza las credenciales según tu entorno.
3. Levanta los contenedores con Docker: `docker-compose up -d`.
4. Importa el esquema de la base de datos desde `context/database.sql`.

## Guía para Desarrolladores
### Arquitectura y Estructura del Código
- `www/controllers/`: concentra la lógica de orquestación de cada módulo y define las rutas que atienden peticiones HTTP.
- `www/services/`: encapsula reglas de negocio y operaciones compartidas entre controladores para mantener el código modular.
- `www/models/`: implementa la capa de acceso a datos, gestionando consultas y transformaciones vinculadas a la base de datos.
- `www/views/`: contiene las vistas y plantillas que se renderizan en el frontend.
- `www/helpers/` y `www/core/`: proveen utilidades transversales (helpers) y componentes internos del framework propio (core) como router, kernel y configuración.
- `www/public/`: expone los puntos de entrada públicos (por ejemplo `index.php`) y archivos estáticos.
- `www/log/`, `www/runtime/` y `www/uploads/`: almacenan archivos generados en tiempo de ejecución (logs, caché, assets subidos); no se versionan como código.

### Datos y Persistencia
- `db/`: volumen local que persiste los datos de MySQL en desarrollo.
- `context/database.sql`: esquema completo de referencia para inicializar la base de datos.
- `www/migrations/`: scripts versionados para evolucionar el esquema cuando se requiera.

### Dependencias y Herramientas
- `www/composer.json` y `www/composer.lock`: definen las dependencias PHP administradas por Composer.
- `www/vendor/`: contiene las librerías instaladas mediante Composer; no debe editarse manualmente y puede regenerarse con `composer install`.
- `Dockerfile` y `docker-compose.yml`: definen la imagen PHP/Apache y la orquestación de servicios (web, MySQL, phpMyAdmin) para desarrollo local.

### Onboarding Rápido
1. Asegúrate de tener Docker, Docker Compose y Composer instalados en tu máquina anfitriona.
2. Sigue los cuatro pasos de **Configuración Local**, verificando que los contenedores `web`, `mysql` y `phpmyadmin` queden en estado `Up` (`docker-compose ps`).
3. Instala las dependencias PHP dentro del contenedor web: `docker-compose exec web composer install`.
4. Accede a `http://localhost:8080` para validar la aplicación y usa `http://localhost:8081` (phpMyAdmin) para comprobar que el esquema importado quedó disponible.

## Flujo de Trabajo (Gitflow)
1. Actualiza la rama `develop`: `git checkout develop && git pull`.
2. Crea tu rama de trabajo con el prefijo `feature/...` a partir de `develop`.
3. Desarrolla y valida tus cambios (incluye pruebas y verificación manual cuando aplique).
4. Abre un Pull Request hacia `develop`; solicita revisión y fusiona solo después de la aprobación.
