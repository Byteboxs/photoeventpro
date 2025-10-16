<?php

namespace app\core\log;

class ConsoleLogWriter implements LogWriterInterface
{
    public function write(string $formattedLog): void
    {
        echo $formattedLog . PHP_EOL;
    }
}
