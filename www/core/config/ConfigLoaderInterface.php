<?php

namespace app\core\config;

interface ConfigLoaderInterface
{
    public function load(): array;
}
