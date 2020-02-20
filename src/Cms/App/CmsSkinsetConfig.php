<?php

namespace Cms\App;

/**
 * Konfiguracja zestawu skór
 */
class CmsSkinsetConfig
{

    /**
     * Dostępne szablony
     * @var array
     */
    private $_skins = [];

    /**
     * Dodaje skórę
     * @param CmsSkinConfig $skinConfig
     * @return CmsSkinsetConfig
     */
    public function addSkin(CmsSkinConfig $skinConfig)
    {
        $this->_skins[] = $skinConfig;
        return $this;
    }

    /**
     * Pobiera dostępne skóry
     * @return CmsSkinConfig[]
     */
    public function getSkins()
    {
        return $this->_skins;
    }

}