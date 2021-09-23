<?php

namespace Cms\Api;

/**
 * Breadcrumb data object
 */
class BreadcrumbData implements DataInterface
{
    public string   $name;
    public int      $order;
    public array    $_links = [];

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setOrder(int $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function setLinks(array $links): self
    {
        $this->_links = $links;
        return $this;
    }

}