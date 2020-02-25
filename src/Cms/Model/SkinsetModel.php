<?php

namespace Cms\Model;

use Cms\App\CmsSkinConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\App\CmsTemplateConfig;

/**
 * Model zestawu skór
 */
class SkinsetModel
{
    //separator w kluczach
    const SEPARATOR = '/';

    /**
     * Konstruktor
     * @var CmsSkinsetConfig
     */
    private $_skinsetConfig;

    /**
     * Konstruktor
     * @param CmsSkinsetConfig $skinsetConfig
     */
    public function __construct(CmsSkinsetConfig $skinsetConfig)
    {
        $this->_skinsetConfig = $skinsetConfig;
    }

    /**
     * Pobiera tablicę szablonów
     * @return array
     */
    public function getTemplatesMultioptions()
    {
        $templates = [];
        //iteracja po skórach
        foreach ($this->_skinsetConfig->getSkins() as $skinConfig) {
            //iteracja po szablonach
            foreach ($skinConfig->getTemplates() as $templateConfig) {
                $templates[$skinConfig->getKey() . self::SEPARATOR . $templateConfig->getKey()] = $templateConfig->getName();
            }
        }
        return $templates;
    }

    /**
     * Zwraca sekcje po szablonie
     * @param string $key
     * @return SkinModelSection[]
     */
    public function getSectionsByKey($key)
    {
        //wyszukiwanie skóry
        if (null === $skinConfig = $this->getSkinConfigByKey($key)) {
            return [];
        }
        //wyszukiwanie szablonu
        if (null === $templateConfig = $this->getTemplateConfigByKey($key)) {
            return [];
        }
        $sections = [];
        foreach ($templateConfig->getSections() as $sectionConfig) {
            $sections[] = new SectionModel($sectionConfig, $skinConfig->getKey() . self::SEPARATOR . $templateConfig->getKey());
        }
        return $sections;
    }

    /**
     * Wybiera skórę po kluczu
     * @param string $key
     * @return CmsSkinConfig
     */
    public function getSkinConfigByKey($key)
    {
        //iteracja po skórach
        foreach ($this->_skinsetConfig->getSkins() as $skinConfig) {
            //porównanie klucza do klucza skóry
            if ($skinConfig->getKey() == substr($key, 0, strlen($skinConfig->getKey()))) {
                return $skinConfig;
            }
        }
    }

    /**
     * Wybiera template po kluczu
     * @param string $key
     * @return CmsTemplateConfig
     */
    public function getTemplateConfigByKey($key)
    {
        //wyszukiwanie skóry
        if (null === $skinConfig = $this->getSkinConfigByKey($key)) {
            return;
        }
        //iteracja po szablonach
        foreach ($skinConfig->getTemplates() as $templateConfig) {
            //klucz szablonu
            $templateKey = $skinConfig->getKey() . self::SEPARATOR . $templateConfig->getKey();
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
        //wyszukiwanie skóry
        if (null === $skinConfig = $this->getSkinConfigByKey($key)) {
            return;
        }
        //wyszukiwanie szablonu
        if (null === $templateConfig = $this->getTemplateConfigByKey($key)) {
            return;
        }
        //wyszukiwanie sekcji
        foreach ($templateConfig->getSections() as $sectionConfig) {
            //klucz sekcji
            $sectionKey = $skinConfig->getKey() . self::SEPARATOR . $templateConfig->getKey() . self::SEPARATOR . $sectionConfig->getKey();
            //porównanie klucza sekcji z odpowiadającym fragmentem klucza
            if ($sectionKey == substr($key, 0, strlen($sectionKey))) {
                return $sectionConfig;
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
        //wyszukiwanie skóry
        if (null === $skinConfig = $this->getSkinConfigByKey($key)) {
            return;
        }
        //wyszukiwanie szablonu
        if (null === $templateConfig = $this->getTemplateConfigByKey($key)) {
            return;
        }
        if (null === $sectionConfig = $this->getSectionConfigByKey($key)) {
            return;
        }
        //iteracja po szablonach
        foreach ($sectionConfig->getWidgets() as $widgetConfig) {
            //porównanie kluczy
            if ($key == $skinConfig->getKey() . self::SEPARATOR . $templateConfig->getKey() . self::SEPARATOR . $sectionConfig->getKey() . self::SEPARATOR . $widgetConfig->getKey()) {
                return $widgetConfig;
            }
        }
    }

}