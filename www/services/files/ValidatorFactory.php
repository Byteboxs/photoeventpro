<?php

namespace app\services\files;

class ValidatorFactory
{
    public static function createDefaultValidationChain(array $config): ValidationHandler
    {
        $sizeValidator = new FileSizeValidator($config['maxFileSize']);
        $typeValidator = new FileTypeValidator($config['allowedFileTypes']);

        // Encadenar los validadores
        $sizeValidator->setNext($typeValidator);

        // Fácilmente extensible para añadir más validadores
        // if (isset($config['validateExtension']) && $config['validateExtension']) {
        //     $extensionValidator = new FileExtensionValidator($config['allowedExtensions']);
        //     $typeValidator->setNext($extensionValidator);
        // }

        return $sizeValidator; // Retorna el primer validador de la cadena
    }
}
