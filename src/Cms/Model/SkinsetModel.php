<?php

namespace Cms\Model;

use Cms\App\CmsSkinsetConfig;

/**
 * Model zestawu skór
 */
class SkinsetModel
{

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
     * Wyszukiwanie modelu skóry
     * @param string $key
     * @return SkinModel
     */
    public function getSkinModelByKey($key)
    {
        //iteracja po skórach
        foreach ($this->_skinsetConfig->getSkins() as $skinConfig) {
            $skinModel = new SkinModel($skinConfig);
            //skóra odnaleziona po kluczu szablonu
            if (null === $skinModel->getTemplateConfigByKey($key)) {
                continue;
            }
            return $skinModel;
        }        
    }

}