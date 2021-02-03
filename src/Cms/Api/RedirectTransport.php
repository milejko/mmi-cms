<?php

namespace Cms\Api;

/**
 * Redirect transport object
 */
class RedirectTransport extends HttpJsonTransport
{
    const DEFAULT_CODE    = 301;

    public int      $code   = self::DEFAULT_CODE;
    public array    $_links = [];

    public function __construct(string $link)
    {
        $this->setHref($link);
    }

    public function setHref(string $href): self
    {
        $this->_links = [(new LinkData())->setHref($href)->setRel('redirect')];
        return $this;
    }
} 