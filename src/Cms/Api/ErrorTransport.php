<?php

namespace Cms\Api;

/**
 * Error transport
 */
class ErrorTransport extends HttpJsonTransport
{
    const DEFAULT_MESSAGE   = 'Internal server error';
    const DEFAULT_CODE      = 500;

    public      string   $message    = self::DEFAULT_MESSAGE;
    protected   int      $code       = self::DEFAULT_CODE;

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}