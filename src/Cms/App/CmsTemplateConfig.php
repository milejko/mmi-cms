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
     */
    private string $name;

    /**
     * Klucz
     */
    private string $key;

    /**
     * Nazwa klasy kontrolera
     */
    private string $controllerClassName;

    /**
     * Długość bufora
     */
    private int $cacheLifeTime = 2592000;

    /**
     * Compatible children keys array
     */
    private array $compatibleChildrenKeys = [];

    /**
     * Allowed on root
     */
    private bool $allowedOnRoot = false;

    /**
     * Sekcje
     */
    private array $sections = [];

    /**
     * Ustawia nazwę
     */
    public function setName(string $name): self
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
     * Sets allowed on root option
     */
    public function setAllowedOnRoot(bool $allowedOnRoot = true): self
    {
        $this->allowedOnRoot = $allowedOnRoot;
        return $this;
    }

    /**
     * Gets allowed on root option
     */
    public function getAllowedOnRoot(): bool
    {
        return $this->allowedOnRoot;
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
     * Ustawia klucz
     * @param string $name
     * @return CmsTemplateConfig
     */
    public function setKey(string $key): self
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
     */
    public function setControllerClassName(string $controllerClassName): self
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
     */
    public function setCacheLifeTime(int $cacheLifeTime): self
    {
        $this->cacheLifeTime = $cacheLifeTime;
        return $this;
    }

    /**
     * Pobiera czas bufora
     */
    public function getCacheLifeTime(): int
    {
        return $this->cacheLifeTime;
    }
}
