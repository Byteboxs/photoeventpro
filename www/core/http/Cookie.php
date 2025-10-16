<?php

namespace app\core\http;

class Cookie
{
    private string $name;
    private string $value = '';
    private int $expires = 0;
    private string $path = '/';
    private string $domain = '';
    private bool $secure = false;
    private bool $httpOnly = true;

    public function __construct(string $name, string $value, int $minutes = 0)
    {
        $this->name = $name;
        $this->value = $value;

        if ($minutes > 0) {
            $this->setExpiration($minutes);
        }
    }

    public function setExpiration(int $minutes): void
    {
        $this->expires = time() + ($minutes * 60);
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function setSecure(bool $secure): void
    {
        $this->secure = $secure;
    }

    public function setHttpOnly(bool $httpOnly): void
    {
        $this->httpOnly = $httpOnly;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    public function send(): bool
    {
        return setcookie(
            $this->name,
            $this->value,
            $this->expires,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );
    }

    public function expire(): void
    {
        setcookie(
            $this->name,
            '',
            time() - 3600,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );

        $this->resetProperties();
    }
    private function resetProperties(): void
    {
        $this->value = '';
        $this->expires = 0;
        $this->path = '/';
        $this->domain = '';
        $this->secure = false;
        $this->httpOnly = true;
    }

    public function __toString(): string
    {
        $str = urlencode($this->name) . '=' . urlencode($this->value);

        if ($this->expires > 0) {
            $str .= '; expires=' . gmdate('D, d M Y H:i:s T', $this->expires);
        }

        if (!empty($this->path)) {
            $str .= '; path=' . $this->path;
        }

        if (!empty($this->domain)) {
            $str .= '; domain=' . $this->domain;
        }

        if ($this->secure) {
            $str .= '; secure';
        }

        if ($this->httpOnly) {
            $str .= '; HttpOnly';
        }

        return $str;
    }
}
