<?php

namespace Cms\App;

/**
 * Konfiguracja skóry
 */
class CmsSkinConfig
{
    /**
     * Nazwa skóry
     */
    private string $name;

    /**
     * Klucz
     */
    private string $key;

    /**
     * Maksymalna głębokość zwracanego menu
     */
    private int $menuMaxDepthReturned = 0;

    /**
     * Preview frontu
     */
    private string $previewUrl = '';

    /**
     * Opcje
     */
    private array $attributes = [];

    /**
     * Dostępne szablony
     */
    private array $templates = [];

    /**
     * Ustawia nazwę
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Pobiera nazwę
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Ustawia klucz
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Pobiera klucz
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public function setMenuMaxDepthReturned(int $menuMaxDepthReturned): self
    {
        $this->menuMaxDepthReturned = $menuMaxDepthReturned;
        return $this;
    }

    public function getMenuMaxDepthReturned(): int
    {
        return $this->menuMaxDepthReturned;
    }

    /**
     * Ustawia url podglądu
     */
    public function setPreviewUrl(string $previewUrl): self
    {
        $this->previewUrl = $previewUrl;
        return $this;
    }

    /**
     * Pobiera url podglądu
     */
    public function getPreviewUrl(): string
    {
        return $this->previewUrl;
    }

    /**
     * Dodaje szablon
     */
    public function addTemplate(CmsTemplateConfig $templateConfig): CmsSkinConfig
    {
        $this->templates[] = $templateConfig;
        return $this;
    }

    /**
     * Pobiera dostępne szablony
     * @return CmsTemplateConfig[]
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * Dodaje atrybuty
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Pobiera atrybuty
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
