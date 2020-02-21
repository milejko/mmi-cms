<?php

namespace Cms\App;

/**
 * Konfiguracja skóry
 */
class CmsSkinConfig
{

    /**
     * Nazwa skóry
     * @var string
     */
    private $_name;

    /**
     * Dostępne szablony
     * @var array
     */
    private $_templates = [];

    /**
     * Ustawia nazwę
     * @param string $name
     * @return CmsSkinConfig
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
     * @return CmsSkinConfig
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
     * Dodaje szablon
     * @param CmsTemplateConfig $templateConfig
     * @return CmsSkinConfig
     */
    public function addTemplate(CmsTemplateConfig $templateConfig)
    {
        $this->_templates[] = $templateConfig;
        return $this;
    }

    /**
     * Pobiera dostępne szablony
     * @return CmsTemplateConfig[]
     */
    public function getTemplates()
    {
        return $this->_templates;
    }

}