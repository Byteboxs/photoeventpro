<?php

namespace app\services\ui\html;

class AlbumSheet
{
    private array $pages = [];
    private int $maxPages = 2;

    public function __construct() {}

    public function addPage(AlbumPage $page): void
    {
        $this->pages[] = $page;
    }

    public function render(): string
    {
        $sheet = HtmlFactory::create('div', ['class' => 'album-sheet']);
        for ($i = 0; $i < $this->maxPages; $i++) {
            $sheet->addChild($this->pages[$i]);
        }
        return $sheet;
    }

    public function __tostring()
    {
        return $this->render();
    }
}
