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
    private string $_name;

    /**
     * Nazwa klasy kontrolera
     * @var string
     */
    private string $_controllerClassName;

    /**
     * Długość bufora
     * @var integer
     */
    private int $_cacheLifeTime = 2592000;

    /**
     * Is nesting allowed
     */
    private bool $_nestingEnabled = false;

    /**
     * Sekcje
     * @var array
     */
    private array $_sections = [];

    /**
     * Ustawia nazwę
     * @param string $name
     * @return CmsTemplateConfig
     */
    public function setName($name): self
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Pobiera nazwę
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Enabling/disabling nesting
     */
    public function setNestingEnabled(bool $nestingEnabled = true): self
    {
        $this->_nestingEnabled = $nestingEnabled;
        return $this;
    }

    /**
     * Gets nesting enabled option
     */
    public function getNestingEnabled(): bool
    {
        return $this->_nestingEnabled;
    }

    /**
     * Ustawia klucz
     * @param string $name
     * @return CmsTemplateConfig
     */
    public function setKey($key): self
    {
        $this->_key = $key;
        return $this;
    }

    /**
     * Pobiera klucz
     * @return string
     */
    public function getKey(): string
    {
        return $this->_key;
    }

    /**
     * Ustawia nazwę klasy kontrolera
     * @param string $controllerClassName
     * @return CmsTemplateConfig
     */
    public function setControllerClassName($controllerClassName): self
    {
        $this->_controllerClassName = $controllerClassName;
        return $this;
    }

    /**
     * Pobiera nazwę klasy kontrolera
     * @return string
     */
    public function getControllerClassName(): string
    {
        return $this->_controllerClassName;
    }

    /**
     * Dodawanie sekcji
     * @param string $name
     * @param CmsSectionConfig $sectionConfig
     * @return CmsTemplateConfig
     */
    public function addSection(CmsSectionConfig $sectionConfig): self
    {
        $this->_sections[] = $sectionConfig;
        return $this;
    }

    /**
     * Zwraca listę sekcji z kompatybilnymi widgetami
     * @return CmsSectionConfig[]
     */
    public function getSections(): array
    {
        return $this->_sections;
    }

        /**
     * Ustawia czas bufora
     * @param integer $cacheLifeTime
     * @return CmsWidgetConfig
     */
    public function setCacheLifeTime($cacheLifeTime): self
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
    public function getCacheLifeTime(): int
    {
        return $this->_cacheLifeTime;
    }

}