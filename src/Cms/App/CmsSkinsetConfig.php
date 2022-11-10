<?php

namespace Cms\App;

/**
 * Konfiguracja zestawu skór
 */
class CmsSkinsetConfig
{
    public const SKIN_NOT_FOND_MESSAGE = 'Skin not found';

    /**
     * Dostępne szablony
     */
    private array $skins = [];

    /**
     * Dodaje skórę
     * @param CmsSkinConfig $skinConfig
     * @return CmsSkinsetConfig
     */
    public function addSkin(CmsSkinConfig $skinConfig): CmsSkinsetConfig
    {
        $this->skins[$skinConfig->getKey()] = $skinConfig;
        return $this;
    }

    /**
     * Gets skin by key
     */
    public function getSkinByKey(string $key): CmsSkinConfig
    {
        if (!isset($this->skins[$key])) {
            throw new CmsSkinNotFoundException(self::SKIN_NOT_FOND_MESSAGE);
        }
        return $this->skins[$key];
    }

    /**
     * Pobiera dostępne skóry
     * @return CmsSkinConfig[]
     */
    public function getSkins(): array
    {
        return $this->skins;
    }
}
