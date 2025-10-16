<?php

namespace app\core\views;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', E_ALL);

use app\controllers\BaseController;
use app\core\Application;
use \app\core\exceptions\InvalidViewException;

// class View
// {
//     private $view;
//     private $viewsFolder;
//     private $fullPath;
//     private $data;
//     private array $components;

//     public function __construct(string $view, array $data = [])
//     {
//         $this->init($view, $data);
//     }

//     function debugPrint($message)
//     {
//         // echo "<pre style='background-color: #f0f0f0; padding: 10px; margin: 5px 0; border: 1px solid #ddd;'>";
//         // echo htmlspecialchars($message);
//         // echo "</pre>";
//     }

//     private function init($view, array $data = [])
//     {
//         $this->view = $view;
//         $this->viewsFolder = Application::$BASE_PATH . APP_DIRECTORY . "/views";
//         $this->components = [];
//         $this->data = $data;
//         $this->fullPath = $this->getFullPathView($this->view);
//     }

//     public function render()
//     {
//         $layout = $this->renderView();
//         if (empty($this->components)) {
//             return $layout;
//         }

//         foreach ($this->components as $key => $value) {
//             if ($value instanceof View) {
//                 // $value = $value->renderView();
//                 $value = $value->render();
//             }
//             $layout = str_replace("{{" . $key . "}}", $value, $layout);
//         }

//         return $layout;
//     }

//     public function renderView(): bool|string
//     {
//         if (!empty($this->data)) {
//             // $this->debugPrint("Tamaño de \$this->data: " . strlen(serialize($this->data)) . " bytes");
//             extract($this->data);
//         }

//         // $this->debugPrint("Antes de ob_start()");
//         ob_start();
//         // $this->debugPrint("Después de ob_start()");
//         // $this->debugPrint("Memoria usada: " . memory_get_usage(true) . " bytes");

//         try {
//             $viewPath = $this->fullPath;
//             $this->debugPrint("Vista a cargar: " . $viewPath);

//             if (file_exists(filename: $viewPath)) {
//                 // $this->debugPrint("Archivo de vista existe, intentando incluir");
//                 include_once $viewPath;
//             } else {
//                 // $this->debugPrint("Archivo de vista no existe");
//                 ob_end_clean();
//                 throw new InvalidViewException(message: "The requested view {$this->view} is not available. The execution of the current view will be truncated.");
//             }
//         } catch (InvalidViewException $e) {
//             // $this->debugPrint("Excepción capturada: " . $e->getMessage());
//             // $this->error = true;
//             (new BaseController())->handleInvalidViewException(e: $e);
//             exit;
//         }

//         // $this->debugPrint("Antes de ob_get_clean()");
//         // $this->debugPrint("Memoria usada: " . memory_get_usage(true) . " bytes");
//         // $this->debugPrint("Pico de memoria: " . memory_get_peak_usage(true) . " bytes");

//         return ob_get_clean();
//     }

//     public function with($view, $value = null)
//     {
//         if (is_array($view)) {
//             $this->components = array_merge($this->components, $view);
//         } else {
//             $this->components[$view] = $value;
//         }
//     }

//     private function getFullPathView(string $view): string
//     {
//         // $this->debugPrint('View::getFullPathView : ' . $view);

//         // Split the view name into parts using dot as the delimiter.
//         $parts = explode('.', $view . '.view.php');

//         // Get the last three parts of the view name and join them with dot as the delimiter.
//         $nombreArchivo = implode('.', array_slice($parts, -3));

//         // Construct the path of the view file by joining the first n-3 parts of the view name with slash as the delimiter,
//         // followed by the joined last three parts of the view name with dot as the delimiter.
//         $path = '/' . implode('/', array_slice($parts, 0, -3)) . '/' . $nombreArchivo;

//         $fullPath = $this->viewsFolder . $path;

//         // var_dump($this->viewsFolder . $path);
//         // Return the path of the view file.
//         return $fullPath;
//     }
// }
class View
{
    /** @var string The view template identifier */
    private string $view;

    /** @var string The base folder for views */
    private string $viewsFolder;

    /** @var string The full file path to the view */
    private string $fullPath;

    /** @var array Data to be passed to the view */
    private array $data;

    /** @var array Components to be included in the view */
    private array $components = [];

    /** @var bool Whether debugging is enabled */
    private bool $debugEnabled;

    /**
     * View constructor
     * 
     * @param string $view View identifier (e.g. 'auth.login')
     * @param array $data Data to be passed to the view
     * @param bool $debugEnabled Whether to enable debugging
     */
    public function __construct(string $view, array $data = [], bool $debugEnabled = false)
    {
        $this->view = $view;
        $this->data = $data;
        $this->debugEnabled = $debugEnabled;
        $this->viewsFolder = Application::$BASE_PATH . APP_DIRECTORY . "/views";
        $this->fullPath = $this->resolveViewPath($view);

        $this->debug("View initialized: $view");
    }

    /**
     * Log debug message if debugging is enabled
     * 
     * @param string $message The message to log
     */
    private function debug(string $message): void
    {
        if ($this->debugEnabled) {
            // Logger::debug("[View] " . $message);
        }
    }

    /**
     * Renders the complete view with all components
     * 
     * @return string The rendered HTML
     */
    public function render(): string
    {
        $layout = $this->renderView();

        if (empty($this->components)) {
            return $layout;
        }

        foreach ($this->components as $key => $value) {
            if ($value instanceof View) {
                $value = $value->render();
            }
            $layout = str_replace("{{" . $key . "}}", $value, $layout);
        }

        return $layout;
    }

    /**
     * Renders the view without components
     * 
     * @return string The rendered view
     * @throws InvalidViewException If the view file doesn't exist
     */
    public function renderView(): string
    {
        $this->debug("Rendering view: {$this->view}");

        if (!empty($this->data)) {
            $this->debug("Extracting data variables: " . implode(", ", array_keys($this->data)));
            extract($this->data, EXTR_SKIP);
        }

        ob_start();

        try {
            $viewPath = $this->fullPath;
            $this->debug("Loading view file: $viewPath");

            if (file_exists($viewPath)) {
                include_once $viewPath;
            } else {
                ob_end_clean();
                throw new InvalidViewException(
                    "The requested view {$this->view} is not available at path: {$viewPath}"
                );
            }
        } catch (InvalidViewException $e) {
            $this->debug("View exception: " . $e->getMessage());
            ob_end_clean();
            (new BaseController())->handleInvalidViewException($e);
            exit;
        }

        return ob_get_clean();
    }

    /**
     * Add components to the view
     * 
     * @param string|array $component Component identifier or array of components
     * @param mixed|null $value Component value if first parameter is string
     * @return self For method chaining
     */
    public function with(string|array $component, mixed $value = null): self
    {
        if (is_array($component)) {
            $this->components = array_merge($this->components, $component);
        } else {
            $this->components[$component] = $value;
        }

        return $this;
    }

    /**
     * Resolve the full path to the view file
     * 
     * @param string $view The view identifier
     * @return string The full path to the view file
     */
    private function resolveViewPath(string $view): string
    {
        // Split the view name into parts using dot as the delimiter
        $parts = explode('.', $view);

        // The file name is the last part with '.view.php' appended
        $fileName = end($parts) . '.view.php';

        // The directory path is made from all but the last part
        $dirPath = count($parts) > 1
            ? implode('/', array_slice($parts, 0, -1))
            : '';

        $fullPath = $this->viewsFolder . '/' . $dirPath . '/' . $fileName;

        $this->debug("Resolved view path: $fullPath");

        return $fullPath;
    }

    /**
     * Add multiple data values to the view
     * 
     * @param array $data Data to add to the view
     * @return self For method chaining
     */
    public function withData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
}
