<?php

namespace Cms\App;

/**
 * Konfiguracja szablonu
 */
class CmsSectionConfig
{
    /**
     * Nazwa szablonu
     * @var string
     */
    private $_name;

    /**
     * Kompatybilne widgety
     * @var array
     */
    private $_widgets = [];

    /**
     * Ustawia nazwę szablonu
     * @param string $name
     * @return CmsSectionConfig
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Pobiera nazwę
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Ustawia klucz
     * @param string $name
     * @return CmsSectionConfig
     */
    public function setKey($key)
    {
        $this->_key = $key;
        return $this;
    }

    /**
     * Pobiera klucz
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * Dodawanie sekcji
     * @param string $name
     * @param CmsWidgetConfig $widgetConfig
     * @return CmsSectionConfig
     */
    public function addWidget(CmsWidgetConfig $widgetConfig)
    {
        $this->_widgets[] = $widgetConfig;
        return $this;
    }

    /**
     * Zwraca listę kompatybilnych widgetów
     * @return CmsWidgetConfig[]
     */
    public function getWidgets()
    {
        return $this->_widgets;
    }
}
