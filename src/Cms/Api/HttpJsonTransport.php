<?php

namespace Cms\Api;

/**
 * HTTP Json transport object
 */
class HttpJsonTransport implements DataInterface, TransportInterface
{
    public const CODE_OK        = 200;
    public const CODE_NOT_FOUND = 404;
    public const CODE_ERROR     = 500;
    public const CODE_MOVED     = 301;
    public const DEFAULT_CODE   = self::CODE_OK;

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
