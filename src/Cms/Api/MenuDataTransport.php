<?php

namespace Cms\Api;

/**
 * Template data object
 */
class MenuDataTransport extends HttpJsonTransport
{
    public array $children = [];

    public function setMenu(array $menu): self
    {
        $this->children = $menu;
        return $this;
    }
}
