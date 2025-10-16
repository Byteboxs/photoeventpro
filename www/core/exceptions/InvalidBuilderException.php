<?php

namespace app\core\exceptions;

use app\core\collections\Map;
use Exception;

class InvalidBuilderException extends Exception
{
    public function __construct(string $msg, $code = 0, Exception $previous = null)
    {
        $message = sprintf(
            "BUILDER: %s",
            $msg
        );
        parent::__construct($message, $code, $previous);
    }
}
