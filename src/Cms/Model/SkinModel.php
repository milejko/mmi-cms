<?php

namespace Cms\Model;

use Cms\App\CmsSkinConfig;
use Mmi\Filter\Url;

class SkinModel
{
    CONST SEPARATOR = '/';
    private $_skin;

    public function __construct(CmsSkinConfig $cmsSkinConfig)
    {
        $this->_skin = $cmsSkinConfig;
    }

    public function getTemplatesMultioptions()
    {
        $templates = [];
        foreach ($this->_skin->getTemplates() as $template) {
            $templates[$this->_getUniqueTemplateKey($template->getName())] = $this->_skin->getName() . ' / ' . $template->getName();
        }
        return $templates;
    }

    public function templateExists($templateKey)
    {
        //iteracja po szablonach
        foreach ($this->_skin->getTemplates() as $template) {
            //szablon niezgodny
            if ($this->_getUniqueTemplateKey($template->getName()) != $templateKey) {
                continue;
            }
            return true;
        }
        return false;
    }

    /**
     * Zwraca sekcje po szablonie
     * @param [type] $templateKey
     * @return SkinModelSection[]
     */
    public function getSectionsByTemplateKey($templateKey)
    {
        //iteracja po szablonach
        foreach ($this->_skin->getTemplates() as $template) {
            //szablon niezgodny
            if ($this->_getUniqueTemplateKey($template->getName()) != $templateKey) {
                continue;
            }
            $sections = [];
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
        foreach ($this->_skin->getTemplates() as $template) {
            foreach ($template->getSections() as $section) {
                foreach ($section->getWidgets() as $widget) {
                    if ($widgetKey == $this->_getUniqueWidgetKey($template->getName(), $section->getName(), $widget->getName())) {
                        return $widget;
                    }
                }
            }
        }
    }

    /**
     * Wyznacza klucz szablonu w skórce
     * @param string $templateName
     * @return string
     */
    private function _getUniqueTemplateKey($templateName)
    {
        return (new Url)->filter($this->_skin->getName()) . self::SEPARATOR . (new Url)->filter($templateName);
    }

    /**
     * Wyznacza klucz szablonu w skórce
     * @param string $templateName
     * @return string
     */
    private function _getUniqueWidgetKey($templateName, $sectionName, $widgetName)
    {
        return $this->_getUniqueTemplateKey($templateName) . self::SEPARATOR . (new Url)->filter($sectionName) . self::SEPARATOR . (new Url)->filter($widgetName);
    }

}