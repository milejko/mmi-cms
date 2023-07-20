<?php

namespace Cms\Api;

/**
 * Breadcrumb data object
 */
class BreadcrumbData implements DataInterface
{
    public int      $id;
    public string   $name;
    public string   $path;
    public string   $template;
    public bool     $blank = false;
    public bool     $visible = true;
    public array    $attributes = [];
    public string   $order;
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

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    public function setBlank(bool $blank = true): self
    {
        $this->blank = $blank;
        return $this;
    }

    public function setVisible(bool $visible = true): self
    {
        $this->visible = $visible;
        return $this;
    }

    public function setAttributes(array $attributes = []): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function setOrder(string $order): self
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
