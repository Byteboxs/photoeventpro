<?php

namespace app\services\ui\html;

class AlbumPage
{
    private ?string $image;
    private ?int $order;

    public function __construct(?string $image = null, ?int $order = null)
    {
        $this->image = $image;
        $this->order = $order;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function render(): string
    {
        $imageData = base64_encode(file_get_contents($this->image));
        $src = 'data: ' . mime_content_type($this->image) . ';base64,' . $imageData;
        $img = HtmlFactory::create('img', [
            'src' => $src,
            'alt' => 'Foto',
        ]);
        return HtmlFactory::create('div', ['class' => 'album-page', 'data-page' => $this->order])
            ->addChild($img)
            ->addChild(HtmlFactory::create('div', ['class' => 'album-page-label'], 'Pagina ' . ($this->order)));
    }

    public function __tostring()
    {
        return $this->render();
    }
}
