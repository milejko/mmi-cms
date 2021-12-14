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
    public function setName(string $name): CmsSkinConfig
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
    public function setKey(string $key): CmsSkinConfig
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