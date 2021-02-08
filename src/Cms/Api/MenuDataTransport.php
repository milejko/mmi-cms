<?php

namespace Cms\Api;

/**
 * Template data object
 */
class MenuDataTransport extends HttpJsonTransport
{
    public array $menu = [];

    public function setMenu(array $menu): self
    {
        $this->menu = $menu;
        return $this;
    }
}