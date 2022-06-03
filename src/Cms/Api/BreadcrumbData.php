<?php

namespace Cms\Api;

/**
 * Breadcrumb data object
 */
class BreadcrumbData implements DataInterface
{
    public int      $id;
    public string   $name;
    public string   $template;
    public bool     $blank = false;
    public bool     $visible = true;
    public int      $order;
    public array    $_links = [];

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function setBlank($blank = true): self
    {
        $this->blank = $blank;
        return $this;
    }

    public function setVisible($visible = true): self
    {
        $this->visible = $visible;
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