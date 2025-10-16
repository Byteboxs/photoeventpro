<?php

namespace app\services\ui\menu;

class MenuJsonLoader
{
    /**
     * Carga un menú desde un archivo JSON
     * 
     * @param string $jsonFilePath Ruta del archivo JSON
     * @return Menu Menú generado a partir del JSON
     * @throws \InvalidArgumentException Si el archivo no existe o no es válido
     * @throws \JsonException Si hay errores en el parseo del JSON
     */
    public static function loadFromFile(string $jsonFilePath): Menu
    {
        if (!file_exists($jsonFilePath)) {
            throw new \InvalidArgumentException("El archivo JSON no existe: {$jsonFilePath}");
        }

        $jsonContent = file_get_contents($jsonFilePath);
        return self::loadFromString($jsonContent);
    }

    /**
     * Carga un menú desde un string JSON
     * 
     * @param string $jsonString Contenido JSON
     * @return Menu Menú generado a partir del JSON
     * @throws \JsonException Si hay errores en el parseo del JSON
     */
    public static function loadFromString(string $jsonString): Menu
    {
        try {
            $menuData = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
            return self::buildMenuFromArray($menuData);
        } catch (\JsonException $e) {
            throw new \JsonException("Error al parsear el JSON: " . $e->getMessage());
        }
    }

    /**
     * Construye un menú a partir de un array de datos
     * 
     * @param array $menuData Datos del menú
     * @return Menu Menú generado
     */
    private static function buildMenuFromArray(array $menuData): Menu
    {
        $builder = new MenuBuilder();

        foreach ($menuData['items'] as $itemData) {
            $menuItem = self::createMenuItem($itemData);

            // Manejar separadores si están presentes
            if (isset($itemData['separator'])) {
                $builder->addSeparator($itemData['separator']);
            }

            if ($menuItem) {
                $builder->addItem($menuItem);
            }
        }

        return $builder->build();
    }

    /**
     * Crea un elemento de menú a partir de datos
     * 
     * @param array $itemData Datos del elemento de menú
     * @return MenuItem|null Elemento de menú creado
     */
    private static function createMenuItem(array $itemData): ?MenuItem
    {
        // Manejar separadores
        if (isset($itemData['separator'])) {
            return null;
        }

        // Preparar subitems si existen
        $subItems = [];
        if (isset($itemData['subItems'])) {
            foreach ($itemData['subItems'] as $subItemData) {
                $subItems[] = self::createMenuItem($subItemData);
            }
        }

        // Crear el elemento de menú
        return MenuItem::create(
            $itemData['label'] ?? 'Menú sin título',
            $itemData['icon'] ?? null,
            $itemData['link'] ?? 'javascript:void(0);',
            $subItems,
            $itemData['active'] ?? false,
            $itemData['external'] ?? false
        );
    }
}
