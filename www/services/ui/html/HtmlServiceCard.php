<?php

namespace app\services\ui\html;

class HtmlServiceCard
{
    private ?HtmlElement $image;
    private ?HtmlElement $discountBadge;
    private ?HtmlElement $stockIndicator;
    private ?HtmlElement $title;
    private ?HtmlElement $price;
    private ?HtmlElement $productDescription;
    private ?HtmlElement $productRating;
    private ?HtmlElement $addToCartButton;
    const ENABLED = 'enabled';
    const DISABLED = 'disabled';
    private string $cardType = self::ENABLED;
    private $dissabledMessage = 'Este servicio esta deshabilitado.';

    public function __construct($imge = null, $discountBadge = null, $stockIndicator = null, $title = null, $price = null, $productDescription = null, $productRating = null, $addToCartButton = null)
    {
        $this->image = $imge;
        $this->discountBadge = $discountBadge;
        $this->stockIndicator = $stockIndicator;
        $this->title = $title;
        $this->price = $price;
        $this->productDescription = $productDescription;
        $this->productRating = $productRating;
        $this->addToCartButton = $addToCartButton;
    }

    public function setDissabledMessage($dissabledMessage)
    {
        $this->dissabledMessage = $dissabledMessage;
    }

    public function toogleCardType()
    {
        if ($this->cardType == self::ENABLED) {
            $this->cardType = self::DISABLED;
        } else {
            $this->cardType = self::ENABLED;
        }
    }

    // setters
    public function setImage($image)
    {
        $this->image = $image;
    }

    public function setDiscountBadge($discountBadge)
    {
        $this->discountBadge = $discountBadge;
    }

    public function setStockIndicator($stockIndicator)
    {
        $this->stockIndicator = $stockIndicator;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setProductDescription($productDescription)
    {
        $this->productDescription = $productDescription;
    }

    public function setProductRating($productRating)
    {
        $this->productRating = $productRating;
    }

    public function setAddToCartButton($addToCartButton)
    {
        $this->addToCartButton = $addToCartButton;
    }

    // getters
    public function getImage()
    {
        return $this->image;
    }

    public function getDiscountBadge()
    {
        return $this->discountBadge;
    }

    public function getStockIndicator()
    {
        return $this->stockIndicator;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getProductDescription()
    {
        return $this->productDescription;
    }

    public function getProductRating()
    {
        return $this->productRating;
    }

    public function getAddToCartButton()
    {
        return $this->addToCartButton;
    }

    private function getDissabledOverlay()
    {
        return HtmlFactory::create('div', ['class' => 'disabled-overlay'])
            ->addChild(HtmlFactory::create('span', ['class' => 'disabled-message'], $this->dissabledMessage));
    }

    public function render(): string
    {
        $disabled = '';
        $disabledOverlay = null;
        if ($this->cardType == self::DISABLED) {
            $disabled = 'disabled';
            $disabledOverlay = $this->getDissabledOverlay();
        }
        $disabled = $this->cardType == self::DISABLED ? 'disabled' : '';
        return Htmlfactory::create('div', ['class' => 'product-card ' . $disabled])
            ->addChild($this->discountBadge)
            ->addChild(
                HtmlFactory::create('div', ['class' => 'product-img-container'])
                    ->addChild($this->image)
            )
            ->addChild(
                HtmlFactory::create('div', ['class' => 'product-info'])
                    ->addChild($disabledOverlay)
                    ->addChild($this->stockIndicator)
                    ->addChild($this->title)
                    ->addChild($this->price)
                    ->addChild($this->productRating)
                    ->addChild($this->productDescription)
                    ->addChild($this->addToCartButton)
            );
    }

    public function __tostring()
    {
        return $this->render();
    }
}
