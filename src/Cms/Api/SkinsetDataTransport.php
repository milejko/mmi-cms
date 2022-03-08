<?php

namespace Cms\Api;

/**
 * Skinset data object
 */
class SkinsetDataTransport extends HttpJsonTransport
{
    public array $scopes = [];

    public function setSkins(array $scopes): self
    {
        $this->scopes = $scopes;
        return $this;
    }
}