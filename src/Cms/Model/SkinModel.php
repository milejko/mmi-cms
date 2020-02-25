<?php

namespace Cms\Model;

use Cms\App\CmsSkinConfig;

/**
 * Model skór
 */
class SkinModel
{
    //separator w kluczach
    const SEPARATOR = '/';

    /**
     * Konfiguracja skóry
     * @var CmsSkinConfig
     */
    private $_skinConfig;

    /**
     * Konstruktor
     * @param CmsSkinConfig $cmsSkinConfig
     */
    public function __construct(CmsSkinConfig $cmsSkinConfig)
    {
        $this->_skinConfig = $cmsSkinConfig;
    }

    public function getTemplatesMultioptions()
    {
        $templates = [];
        //iteracja po szablonach
        foreach ($this->_skinConfig->getTemplates() as $templateConfig) {
            $templates[$this->_getUniqueTemplateKey($templateConfig->getKey())] = $templateConfig->getName();
        }
        return $templates;
    }

    /**
     * Zwraca sekcje po szablonie
     * @param string $templateKey
     * @return SkinModelSection[]
     */
    public function getSectionsByTemplateKey($templateKey)
    {
        //iteracja po szablonach
        foreach ($this->_skinConfig->getTemplates() as $template) {
            //szablon niezgodny
            if ($templateKey != $this->_getUniqueTemplateKey($template->getKey())) {
                continue;
            }
            $sections = [];
            //iteracja po sekcjach
            foreach ($template->getSections() as $section) {
                $sections[] = new SkinModelSection($section, $templateKey);
            } 
            //zwrot sekcji
            return $sections;
        }
    }

    /**
     * Wybiera template po kluczu
     * @param string $key
     * @return CmsTemplateConfig
     */
    public function getTemplateConfigByKey($key)
    {
        //iteracja po szablonach
        foreach ($this->_skinConfig->getTemplates() as $templateConfig) {
            //klucz szablonu
            $templateKey = $this->_getUniqueTemplateKey($templateConfig->getKey());
            //porównanie klucza szablonu z odpowiadającym fragmentem klucza
            if ($templateKey == substr($key, 0, strlen($templateKey))) {
                return $templateConfig;
            }
        }
    }

    /**
     * Pobiera sekcję po kluczu
     * @param string $key
     * @return CmsSectionConfig
     */
    public function getSectionConfigByKey($key)
    {
        //iteracja po szablonach
        foreach ($this->_skinConfig->getTemplates() as $templateConfig) {
            //iteracja po sekcjach
            foreach ($templateConfig->getSections() as $sectionConfig) {
                //klucz sekcji
                $sectionKey = $this->_getUniqueTemplateKey($templateConfig->getKey() . self::SEPARATOR . $sectionConfig->getKey());
                //porównanie klucza sekcji z odpowiadającym fragmentem klucza
                if ($sectionKey == substr($key, 0, strlen($sectionKey))) {
                    return $sectionConfig;
                }
            }
        }
    }

    /**
     * Pobiera widget po kluczu
     * @param string $key
     * @return CmsWidgetConfig
     */
    public function getWidgetConfigByKey($key)
    {
        //iteracja po szablonach
        foreach ($this->_skinConfig->getTemplates() as $templateConfig) {
            //iteracja po sekcjach
            foreach ($templateConfig->getSections() as $sectionConfig) {
                //iteracja po widgetach
                foreach ($sectionConfig->getWidgets() as $widgetConfig) {
                    //porównanie kluczy
                    if ($key == $this->_getUniqueTemplateKey($templateConfig->getKey()) . self::SEPARATOR . $sectionConfig->getKey() . self::SEPARATOR . $widgetConfig->getKey()) {
                        return $widgetConfig;
                    }
                }
            }
        }
    }

    /**
     * Wyznacza klucz szablonu w skórce
     * @param string $templateKey
     * @return string
     */
    private function _getUniqueTemplateKey($templateKey)
    {
        return $this->_skinConfig->getKey() . self::SEPARATOR . $templateKey;
    }

}