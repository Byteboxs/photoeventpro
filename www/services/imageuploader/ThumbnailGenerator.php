<?php

namespace app\services\imageuploader;

interface ThumbnailGenerator
{
    public function generate(string $sourcePath, string $destPath, $newWidth): void;
}
