<?php

namespace Cms\App;

use Mmi\Http\Cookie;
use Mmi\Session\SessionSpace;

/**
 * Konfiguracja scope
 */
class CmsScopeConfig
{
    private const COOKIE_LIFETIME = 3600 * 24 * 365;
    private const SCOPE_SESSION_SPACE = 'cms-scope';
    private const COOKIE_NAME = 'cms-scope';

    private SessionSpace $space;

    public function __construct()
    {
        $this->space = new SessionSpace(self::SCOPE_SESSION_SPACE);
    }

    public function getName(): ?string
    {
        $cookie = new Cookie();
        $cookie->match(self::COOKIE_NAME);
        return $this->space->name ? $this->space->name : $cookie->getValue();
    }

    public function setName(string $name): self
    {
        new Cookie(self::COOKIE_NAME, $name, null, time() + self::COOKIE_LIFETIME);
        $this->space->name = $name;
        return $this;
    }
}
