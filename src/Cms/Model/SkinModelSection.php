<?php

namespace Cms\Model;

use Cms\App\CmsSectionConfig;
use Mmi\Filter\Url;

class SkinModelSection
{

    CONST SEPARATOR = '/';

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
        return $this->_templateKey . self::SEPARATOR . (new Url)->filter($this->_section->getName());
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getAvailableWidgets()
    {
        $availableWidges = [];
        foreach ($this->_section->getWidgets() as $widget)
        {
            $availableWidges[$this->getKey() . self::SEPARATOR . (new Url)->filter($widget->getName())] = $widget;
        }
        return $availableWidges;
    }

}