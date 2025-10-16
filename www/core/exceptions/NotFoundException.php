<?php

namespace app\core\exceptions;

class NotFoundException extends \Exception
{
    public function __construct(string $url, $code = 0, \Exception $previous = null)
    {
        $message = sprintf(
            "ROUTER: %s not found.",
            $url
        );
        parent::__construct($message, $code, $previous);
    }
}
