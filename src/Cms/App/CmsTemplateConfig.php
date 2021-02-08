<?php

namespace Cms\App;

use Mmi\App\KernelException;

/**
 * Konfiguracja szablonu
 */
class CmsTemplateConfig
{

    /**
     * Nazwa szablonu
     * @var string
     */
    private $_name;

    /**
     * Nazwa klasy kontrolera
     * @var string
     */
    private $_controllerClassName;

    /**
     * Sekcje
     * @var array
     */
    private $_sections = [];

    /**
     * Ustawia nazwę
     * @param string $name
     * @return CmsTemplateConfig
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
     * @return CmsTemplateConfig
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
     * Ustawia nazwę klasy kontrolera
     * @param string $controllerClassName
     * @return CmsTemplateConfig
     */
    public function setControllerClassName($controllerClassName)
    {
        $this->_controllerClassName = $controllerClassName;
        return $this;
    }

    /**
     * Pobiera nazwę klasy kontrolera
     * @return string
     */
    public function getControllerClassName()
    {
        return $this->_controllerClassName;
    }

    /**
     * Dodawanie sekcji
     * @param string $name
     * @param CmsSectionConfig $sectionConfig
     * @return CmsTemplateConfig
     */
    public function addSection(CmsSectionConfig $sectionConfig)
    {
        $this->_sections[] = $sectionConfig;
        return $this;
    }

    /**
     * Zwraca listę sekcji z kompatybilnymi widgetami
     * @return CmsSectionConfig[]
     */
    public function getSections()
    {
        return $this->_sections;
    }

}