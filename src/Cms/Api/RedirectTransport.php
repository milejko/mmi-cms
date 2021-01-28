<?php

namespace Cms\Api;

/**
 * Redirect transport object
 */
class RedirectTransport extends HttpJsonTransport
{
    const DEFAULT_CODE    = 301;

    public string   $redirectTo;
    public int      $code = self::DEFAULT_CODE;

    public function setRedirectTo(string $redirectTo): self
    {
        $this->redirectTo = $redirectTo;
        return $this;
    }
}