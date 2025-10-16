<?php

namespace app\core\log;

class LogWriterFactory
{
    public static function createFileLogWriter(string $filePath): LogWriterInterface
    {
        return new FileLogWriter($filePath);
    }

    public static function createConsoleLogWriter(): LogWriterInterface
    {
        return new ConsoleLogWriter();
    }
}
