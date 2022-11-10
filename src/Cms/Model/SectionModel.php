<?php

namespace Cms\Model;

use Cms\App\CmsSectionConfig;

/**
 * Model sekcji w skórze
 */
class SectionModel
{
    //separator
    public const SEPARATOR = '/';

    private $_templateKey;
    private $_sectionConfig;

    public function __construct(CmsSectionConfig $sectionConfig, $templateKey)
    {
        $this->_sectionConfig = $sectionConfig;
        $this->_templateKey = $templateKey;
    }

    public function getName()
    {
        return $this->_sectionConfig->getName();
    }

    public function getKey()
    {
        return $this->_templateKey . self::SEPARATOR . $this->_sectionConfig->getKey();
    }

    /**
     * Pobiera dostępne widgety
     * @return CmsWidgetConfig[]
     */
    public function getAvailableWidgets()
    {
        $availableWidges = [];
        foreach ($this->_sectionConfig->getWidgets() as $widget) {
            $availableWidges[$this->getKey() . self::SEPARATOR . $widget->getKey()] = $widget;
        }
        return $availableWidges;
    }
}
