<?php

namespace app\core\exceptions;

use app\core\collections\Map;
use Exception;

class ModelValidationException extends Exception
{
    public function __construct(Map $errors, $code = 0, Exception $previous = null)
    {
        $msg = '';
        for ($iter = $errors->getIterator(); $iter->hasNext();) {
            $error = $iter->next();
            $msg .= $error->value;
        }
        $message = sprintf(
            "MODELO: %s",
            $msg
        );
        parent::__construct($message, $code, $previous);
    }
}
