<?php

namespace Cms\Api;

/**
 * Skinset data object
 */
class SkinsetDataTransport extends HttpJsonTransport
{
    public array $skins = [];

    public function setSkins(array $skins): self
    {
        $this->skins = $skins;
        return $this;
    }
}