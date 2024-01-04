<?php

namespace Cms\Api;

/**
 * Template data object
 */
class MenuDataTransport extends HttpJsonTransport
{
    public array $children = [];
    public array $_links = [];

    public function setMenu(array $menu): self
    {
        $this->children = $menu;
        return $this;
    }
}
