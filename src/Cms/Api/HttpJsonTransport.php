<?php

namespace Cms\Api;

/**
 * HTTP Json transport object
 */
class HttpJsonTransport implements DataInterface, TransportInterface
{
    const CODE_OK        = 200;
    const CODE_NOT_FOUND = 404;
    const CODE_ERROR     = 500;
    const CODE_MOVED     = 301;
    const DEFAULT_CODE   = self::CODE_OK;

    protected int $code = self::DEFAULT_CODE;

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function toString(): string
    {
        return \json_encode($this);
    }
}