<?php

namespace app\services\ui\table;

class BS5ProductoCard
{
    private $image = '';
    private $alt = '';
    private $title = '';
    private $description = '';
    private $tipoSangre = '';
    private $precio = '';
    private $link = '';
    private $html = '';

    public function __construct() {}

    public function create(array $data)
    {
        $this->image = $data['image'];
        $this->alt = $data['alt'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->tipoSangre = $data['tipoSangre'];
        $this->precio = $data['precio'];
        $this->link = $data['link'];
    }


    public function render(): string
    {
        $this->html = sprintf(
            '
            <div class="col-sm-4">
                <div class="card" style="width: 17rem;">
                    <img src="%s" class="card-img-top" alt="%s">
                    <div class="card-body" style="color: #1d3557">
                        <h3><b>%s</b></h3>
                        <p class="card-text" style="font-size: 0.8em"><b>%s:</b> %s</p>
                        <p class="card-text" style="font-size: 1em"><b>%s</b></p>
                        <a href="%s" class="btn btn-danger" style="background-color: #11CECE; border-color: #11CECE;">
                            <i class="fas fa-cart-plus"></i> Agregar al carrito
                        </a>
                    </div>
                </div>
            </div>',
            $this->image,
            $this->alt,
            $this->tipoSangre,
            $this->title,
            $this->description,
            $this->precio,
            $this->link
        );
        return $this->html;
    }

    public function __tostring()
    {
        return $this->render();
    }

    public function setImage(string $image)
    {
        $this->image = $image;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setTipoSangre(string $tipoSangre)
    {
        $this->tipoSangre = $tipoSangre;
    }

    public function setPrecio(string $precio)
    {
        $this->precio = $precio;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
    }

    public function setAlt(string $alt)
    {
        $this->alt = $alt;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTipoSangre()
    {
        return $this->tipoSangre;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getAlt()
    {
        return $this->alt;
    }
}
