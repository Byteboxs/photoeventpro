<?php

namespace app\core\log;

interface LogWriterInterface
{
    public function write(string $formattedLog): void;
}
