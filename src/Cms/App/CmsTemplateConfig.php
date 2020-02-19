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
     * Nazwa szablonu .tpl
     * @var string
     */
    private $_displayTemplate;

    /**
     * Czas bufora
     * @var integer
     */
    private $_cacheLifeTime = 2592000;

    /**
     * Sekcje
     * @var array
     */
    private $_sections = [];

    /**
     * Ustawia nazwę szablonu
     * @param string $name
     * @return CmsTemplateConfig
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Pobiera nazwę szablonu .tpl
     * @return string
     */
    public function getDisplayTemplate()
    {
        return $this->_displayTemplate;
    }

    /**
     * Ustawia nazwę szablonu .tpl
     * @param string $displayTemplate
     * @return CmsTemplateConfig
     */
    public function setDisplayTemplate($displayTemplate)
    {
        $this->_displayTemplate = $displayTemplate;
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
     * Ustawia czas bufora
     * @param integer $cacheLifeTime
     * @return CmsTemplateConfig
     */
    public function setCacheLifeTime($cacheLifeTime)
    {
        //walidacja
        if (!is_int($cacheLifeTime)) {
            throw new KernelException('Cache lifetime invalid');
        }
        $this->_cacheLifeTime = $cacheLifeTime;
        return $this;
    }

    /**
     * Pobiera czas bufora
     * @return integer
     */
    public function getCacheLifeTime()
    {
        return $this->_cacheLifeTime;
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