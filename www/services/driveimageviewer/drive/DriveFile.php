<?php

namespace app\services\driveimageviewer\drive;

class DriveFile
{
    private $id;
    private $name;
    private $mimeType;

    public function __construct(string $id, string $name, string $mimeType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->mimeType = $mimeType;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
