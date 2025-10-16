<?php

namespace app\core\log;

use app\core\Application;

class Logger
{
    private LogWriterInterface $writer;
    private LogFormatterInterface $formatter;

    public function __construct(LogWriterInterface $writer, LogFormatterInterface $formatter)
    {
        $this->writer = $writer;
        $this->formatter = $formatter;
    }

    public function log(string $level, string $message): void
    {
        $isDebugging = Application::$app->config->get('debug');
        if ($isDebugging) {
            $logData = [
                'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
                'level' => $level,
                'message' => $message,
            ];

            $formattedLog = $this->formatter->format($logData);
            $this->writer->write($formattedLog);
        }
    }
}
