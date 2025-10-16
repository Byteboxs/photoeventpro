<?php

namespace app\services\ui\badge;

use app\core\Singleton;

class BadgeBuilderService extends Singleton
{
    public const TYPE_DEFAULT = 'default';
    public const TYPE_PILL = 'pill';

    public const COLOR_PRIMARY = 'text-bg-primary';
    public const COLOR_SECONDARY = 'text-bg-secondary';
    public const COLOR_SUCCESS = 'text-bg-success';
    public const COLOR_DANGER = 'text-bg-danger';
    public const COLOR_WARNING = 'text-bg-warning';
    public const COLOR_INFO = 'text-bg-info';
    public const COLOR_LIGHT = 'text-bg-light';
    public const COLOR_DARK = 'text-bg-dark';

    protected function __construct(string $text, string $color = self::COLOR_PRIMARY, string $type = self::TYPE_DEFAULT)
    {
        parent::__construct($text, $color, $type);
    }

    public function setText(string $text): self
    {
        $this->args[0] = $text;
        return $this;
    }

    public function setColor(string $color): self
    {
        $this->args[1] = $color;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->args[2] = $type;
        return $this;
    }

    public function getText(): string
    {
        return $this->args[0];
    }

    public function getColor(): string
    {
        return $this->args[1];
    }

    public function getType(): string
    {
        return $this->args[2];
    }

    public function render()
    {
        $badge = match ($this->getType()) {
            self::TYPE_PILL => new PillBadge($this->getText(), $this->getColor()),
            default => new Badge($this->getText(), $this->getColor()),
        };
        return $badge->render();
    }
}
