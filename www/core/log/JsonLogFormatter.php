<?php

namespace app\core\log;

class JsonLogFormatter implements LogFormatterInterface
{
    public function format(array $logData): string
    {
        return json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
