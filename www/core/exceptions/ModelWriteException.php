<?php

namespace app\core\exceptions;

use Exception;

class ModelWriteException extends Exception
{
    public function __construct(string $modelName, string $attributes = '', $code = 0, Exception $previous = null)
    {
        $message = sprintf(
            "Model: %s no tiene atributos [" . $attributes . "] de escritura definidos, no se puede modificar.",
            $modelName
        );
        parent::__construct($message, $code, $previous);
    }
}
