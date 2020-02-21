<?php

namespace Cms\Model;

use App\Registry;
use Cms\App\CmsSkinConfig;
use Mmi\App\FrontController;

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
        foreach ($this->_skinConfig->getTemplates() as $template) {
            $templates[$this->_getUniqueTemplateKey($template->getKey())] = $template->getName();
        }
        return $templates;
    }

    /**
     * Wybiera template po kluczu
     * @param string $templateKey
     * @return CmsTemplateConfig
     */
    public function getTemplateByKey($templateKey)
    {
        //iteracja po szablonach
        foreach ($this->_skinConfig->getTemplates() as $template) {
            //szablon niezgodny
            if ($this->_getUniqueTemplateKey($template->getKey()) != $templateKey) {
                continue;
            }
            //zwrot szablonu
            return $template;
        }
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
            if ($this->_getUniqueTemplateKey($template->getKey()) != $templateKey) {
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
     * Pobiera widget po skluczu
     * @param string $widgetKey
     * @return CmsWidgetConfig
     */
    public function getWidgetByKey($widgetKey)
    {
        //iteracja po szablonach
        foreach ($this->_skinConfig->getTemplates() as $template) {
            //iteracja po sekcjach
            foreach ($template->getSections() as $section) {
                //iteracja po widgetach
                foreach ($section->getWidgets() as $widget) {
                    //widget odnaleziony
                    if ($widgetKey == $this->_getUniqueWidgetKey($template->getKey(), $section->getKey(), $widget->getKey())) {
                        return $widget;
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

    /**
     * Wyznacza klucz szablonu w skórce
     * @param string $templateKey
     * @param string $sectionKey
     * @param string $widgetKey
     * @return string
     */
    private function _getUniqueWidgetKey($templateKey, $sectionKey, $widgetKey)
    {
        return $this->_getUniqueTemplateKey($templateKey) . self::SEPARATOR . $sectionKey . self::SEPARATOR . $widgetKey;
    }

}