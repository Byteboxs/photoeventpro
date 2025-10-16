<?php

namespace app\core\log;

interface LogFormatterInterface
{
    public function format(array $logData): string;
}
