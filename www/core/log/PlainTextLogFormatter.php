<?php

namespace app\core\log;

class PlainTextLogFormatter implements LogFormatterInterface
{
    public function format(array $logData): string
    {
        return sprintf("[%s] %s: %s", $logData['timestamp'], $logData['level'], $logData['message']);
    }
}
