<?php

namespace Cms\Api;

/**
 * Error transport
 */
class ErrorTransport extends HttpJsonTransport
{
    public const DEFAULT_MESSAGE   = 'Internal server error';
    public const DEFAULT_CODE      = 500;

    public string   $message    = self::DEFAULT_MESSAGE;
    protected int      $code       = self::DEFAULT_CODE;

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
