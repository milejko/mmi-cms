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
    private string $name;

    /**
     * Nazwa klasy kontrolera
     * @var string
     */
    private string $controllerClassName;

    /**
     * Długość bufora
     * @var integer
     */
    private int $cacheLifeTime = 2592000;

    /**
     * Is nesting allowed
     */
    private bool $nestingEnabled = false;

    /**
     * Compatible children keys array
     */
    private array $compatibleChildrenKeys = [];

    /**
     * Compatible nesting levels
     */
    private array $compatibleNestingLevels = [];

    /**
     * Sekcje
     * @var array
     */
    private array $sections = [];

    /**
     * Ustawia nazwę
     * @param string $name
     * @return CmsTemplateConfig
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Pobiera nazwę
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Enabling/disabling nesting
     */
    public function setNestingEnabled(bool $nestingEnabled = true): self
    {
        $this->nestingEnabled = $nestingEnabled;
        return $this;
    }

    /**
     * Gets nesting enabled option
     */
    public function getNestingEnabled(): bool
    {
        return $this->nestingEnabled;
    }

    /**
     * Sets compatible children keys like [folder, article]
     */
    public function setCompatibleChildrenKeys(array $compatibleChildrenKeys): self
    {
        $this->compatibleChildrenKeys = $compatibleChildrenKeys;
        return $this;
    }

    /**
     * Gets compatible children keys
     */
    public function getCompatibleChildrenKeys(): array
    {
        return $this->compatibleChildrenKeys;
    }

    /**
     * Sets compatible nesting levels like [0, 1]
     */
    public function setCompatibleNestingLevels(array $compatibleNestingLevels): self
    {
        $this->compatibleNestingLevels = $compatibleNestingLevels;
        return $this;
    }

    /**
     * Gets compatible nesting levels
     */
    public function getCompatibleNestingLevels(): array
    {
        return $this->compatibleNestingLevels;
    }

    /**
     * Ustawia klucz
     * @param string $name
     * @return CmsTemplateConfig
     */
    public function setKey($key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Pobiera klucz
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Ustawia nazwę klasy kontrolera
     * @param string $controllerClassName
     * @return CmsTemplateConfig
     */
    public function setControllerClassName($controllerClassName): self
    {
        $this->controllerClassName = $controllerClassName;
        return $this;
    }

    /**
     * Pobiera nazwę klasy kontrolera
     * @return string
     */
    public function getControllerClassName(): string
    {
        return $this->controllerClassName;
    }

    /**
     * Dodawanie sekcji
     * @param string $name
     * @param CmsSectionConfig $sectionConfig
     * @return CmsTemplateConfig
     */
    public function addSection(CmsSectionConfig $sectionConfig): self
    {
        $this->sections[] = $sectionConfig;
        return $this;
    }

    /**
     * Zwraca listę sekcji z kompatybilnymi widgetami
     * @return CmsSectionConfig[]
     */
    public function getSections(): array
    {
        return $this->sections;
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
        $this->cacheLifeTime = $cacheLifeTime;
        return $this;
    }

    /**
     * Pobiera czas bufora
     * @return integer
     */
    public function getCacheLifeTime(): int
    {
        return $this->cacheLifeTime;
    }

}