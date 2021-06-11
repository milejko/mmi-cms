<?php

namespace Cms\App;

use Mmi\Session\SessionSpace;

/**
 * Konfiguracja scope
 */
class CmsScopeConfig
{
    private const SCOPE_SESSION_SPACE = 'cms-scope';

    private SessionSpace $space;

    public function __construct()
    {
        $this->space = new SessionSpace(self::SCOPE_SESSION_SPACE);
    }

    public function getName(): ?string
    {
        return $this->space->name;
    }

    public function setName(string $name): self
    {
        $this->space->name = $name;
        return $this;
    }

}