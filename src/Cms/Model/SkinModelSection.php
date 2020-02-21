<?php

namespace Cms\Model;

use Cms\App\CmsSectionConfig;

/**
 * Model sekcji w skórze
 */
class SkinModelSection
{
    //separator
    const SEPARATOR = '/';

    private $_section;
    private $_templateKey;

    public function __construct(CmsSectionConfig $section, $templateKey)
    {
        $this->_section = $section;
        $this->_templateKey = $templateKey;
    }

    public function getName()
    {
        return $this->_section->getName();
    }

    public function getKey()
    {
        return $this->_templateKey . self::SEPARATOR . $this->_section->getKey();
    }

    /**
     * Pobiera dostępne widgety
     * @return CmsWidgetConfig[]
     */
    public function getAvailableWidgets()
    {
        $availableWidges = [];
        foreach ($this->_section->getWidgets() as $widget) {
            $availableWidges[$this->getKey() . self::SEPARATOR . $widget->getKey()] = $widget;
        }
        return $availableWidges;
    }

}