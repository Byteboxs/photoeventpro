<?php

namespace app\core\exceptions;

use app\core\collections\Map;
use Exception;

class MethodNameNotFoundException extends Exception
{
    public function __construct(string $name, $code = 0, Exception $previous = null)
    {
        $message = sprintf(
            "ROUTER: El metodo %s no existe.",
            $name
        );
        parent::__construct($message, $code, $previous);
    }
}
