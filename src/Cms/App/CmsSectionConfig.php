<?php

namespace Cms\App;

/**
 * Konfiguracja szablonu
 */
class CmsSectionConfig
{
    /**
     * Nazwa szablonu
     */
    private string $name;

    /**
     * Klucz szablonu
     */
    private string $key;

    /**
     * Kompatybilne widgety
     * @var array
     */
    private array $widgets = [];

    /**
     * Ustawia nazwę szablonu
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

    /**
     * Dodawanie widgeta
     */
    public function addWidget(CmsWidgetConfig $widgetConfig): self
    {
        $this->widgets[] = $widgetConfig;
        return $this;
    }

    /**
     * Zwraca listę kompatybilnych widgetów
     * @return CmsWidgetConfig[]
     */
    public function getWidgets(): array
    {
        return $this->widgets;
    }
}
