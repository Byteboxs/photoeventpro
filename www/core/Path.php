<?php

namespace app\core;

class Path
{
    public static function getBasePath($path = '')
    {
        // Obtiene el directorio raíz del servidor web
        $basePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

        // Concatena la ruta del directorio base con la ruta proporcionada
        return $basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public static function baseUrl($filePath)
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($filePath) . '/';
        // echo $baseUrl;
        return $baseUrl;
    }

    public static function getBaseUrl()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];

        $baseUrl = $protocol . $domainName;

        return $baseUrl;
    }

    public static function getAppDirectory($basePath, $projectRoot)
    {
        // Verificamos que la ruta del proyecto sea un subdirectorio de la ruta base
        if (strpos($projectRoot, $basePath) !== 0) {
            return "La ruta del proyecto no es un subdirectorio de la ruta base";
        }

        // Obtenemos la parte de la ruta que va después de la ruta base
        $appDirectory = substr($projectRoot, strlen($basePath));

        if ($appDirectory === '') {
            return '/';
        } else {
            return $appDirectory;
        }
    }
}
