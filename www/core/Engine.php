<?php

namespace app\core;

class Engine
{
    private $name;
    private $view;
    private $data;

    private $viewPath;

    public function __construct()
    {
        $this->viewPath = Path::getBasePath() . '/views';
    }

    /**
     * Get the path of a view file based on the given view name.
     *
     * @param string $view The name of the view.
     * @return string The path of the view file.
     */
    public function getView(string $view): string
    {
        // Split the view name into parts using dot as the delimiter.
        $parts = explode('.', $view . '.view.php');

        // Get the last three parts of the view name and join them with dot as the delimiter.
        $nombreArchivo = implode('.', array_slice($parts, -3));

        // Construct the path of the view file by joining the first n-3 parts of the view name with slash as the delimiter,
        // followed by the joined last three parts of the view name with dot as the delimiter.
        $path = '/' . implode('/', array_slice($parts, 0, -3)) . '/' . $nombreArchivo;

        // Return the path of the view file.
        return $this->viewPath . $path;
    }

    public function get($name, $data = [])
    {
        $this->name = $name;
        $this->view = $this->getView($this->name);
        $this->data = $data;
        extract($data);

        ob_start();

        try {
            if (file_exists($this->view)) {
                include $this->view;
            } else {
                throw new \Exception("Vista no encontrada: " . $this->name . ' -> ' . $this->view);
            }
        } catch (\Exception $e) {
            ob_end_clean();
            return "Error: " . $e->getMessage();
        }

        $output = ob_get_clean();
        return $output;
    }
}
