<?php

namespace Cms\Transport;

/**
 * Error transport object
 */
class ErrorTransport implements TransportInterface
{
    const DEFAULT_ERROR  = 'Unknown API error';
    const DEFAULT_STATUS = 500;

    public string $error;
    public int $status;

    public function __construct($error = self::DEFAULT_ERROR, $status = self::DEFAULT_STATUS)
    {
        $this->error  = $error;
        $this->status = $status;
    }
}