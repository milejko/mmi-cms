<?php

namespace Cms\Model;

use Cms\App\CmsSectionConfig;
use Cms\App\CmsSkinConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\App\CmsTemplateConfig;
use Cms\App\CmsWidgetConfig;

/**
 * Model zestawu skór
 */
class SkinsetModel
{
    //separator w kluczach
    public const SEPARATOR = '/';

    /**
     * Konstruktor
     * @var CmsSkinsetConfig
     */
    private $skinsetConfig;

    public function __construct(CmsSkinsetConfig $skinsetConfig)
    {
        $this->skinsetConfig = $skinsetConfig;
    }

    public function getAllowedTemplateKeysBySkinKey(string $key): array
    {
        if (null === $skin = $this->getSkinConfigByKey($key)) {
            return [];
        }
        $allowedTemplateKeys = [];
        foreach ($skin->getTemplates() as $template) {
            $allowedTemplateKeys[] = $key . self::SEPARATOR . $template->getKey();
        }
        return $allowedTemplateKeys;
    }

    /**
     * Zwraca sekcje po szablonie
     * @return SectionModel[]
     */
    public function getSectionsByKey(string $key): array
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
     */
    public function getSkinConfigByKey(string $key): ?CmsSkinConfig
    {
        //iteracja po skórach
        foreach ($this->skinsetConfig->getSkins() as $skinConfig) {
            //porównanie klucza do klucza skóry
            if ($skinConfig->getKey() == substr($key, 0, strlen($skinConfig->getKey()))) {
                return $skinConfig;
            }
        }
        return null;
    }

    /**
     * Wybiera template po kluczu
     */
    public function getTemplateConfigByKey(string $key): ?CmsTemplateConfig
    {
        //wyszukiwanie skóry
        if (null === $skinConfig = $this->getSkinConfigByKey($key)) {
            return null;
        }
        $keyElements = explode('/', $key);
        //iteracja po szablonach
        foreach ($skinConfig->getTemplates() as $templateConfig) {
            //porównanie klucza skorki i szablonu z odpowiadającym elementem klucza
            if ($skinConfig->getKey() === $keyElements[0] && $templateConfig->getKey() === $keyElements[1]) {
                return $templateConfig;
            }
        }
        return null;
    }

    /**
     * Pobiera sekcję po kluczu
     */
    public function getSectionConfigByKey(string $key): ?CmsSectionConfig
    {
        //wyszukiwanie skóry
        if (null === $skinConfig = $this->getSkinConfigByKey($key)) {
            return null;
        }
        //wyszukiwanie szablonu
        if (null === $templateConfig = $this->getTemplateConfigByKey($key)) {
            return null;
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
        return null;
    }

    /**
     * Pobiera widget po kluczu
     */
    public function getWidgetConfigByKey(string $key): ?CmsWidgetConfig
    {
        //wyszukiwanie skóry
        if (null === $skinConfig = $this->getSkinConfigByKey($key)) {
            return null;
        }
        //wyszukiwanie szablonu
        if (null === $templateConfig = $this->getTemplateConfigByKey($key)) {
            return null;
        }
        if (null === $sectionConfig = $this->getSectionConfigByKey($key)) {
            return null;
        }
        //iteracja po szablonach
        foreach ($sectionConfig->getWidgets() as $widgetConfig) {
            //porównanie kluczy
            if ($key == $skinConfig->getKey() . self::SEPARATOR . $templateConfig->getKey() . self::SEPARATOR . $sectionConfig->getKey() . self::SEPARATOR . $widgetConfig->getKey()) {
                return $widgetConfig;
            }
        }
        return null;
    }
}
