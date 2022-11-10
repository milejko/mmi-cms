<?php

namespace Cms\App;

use Mmi\App\KernelException;

/**
 * Konfiguracja widgeta
 */
class CmsWidgetConfig
{
    /**
     * Nazwa widgeta
     * @var string
     */
    private string $_name;

    /**
     * Klucz widgeta
     * @var string
     */
    private string $_key;

    /**
     * Nazwa klasy kontrolera
     * @var string
     */
    private string $_controllerClassName;

    /**
     * Minimalna ilość wystąpień
     * @var integer
     */
    private int $_minOccurrence = 0;

    /**
     * Maksymalna ilość wystąpień
     * @var integer
     */
    private int $_maxOccurrence = 1000;

    /**
     * Długość bufora
     * @var integer
     */
    private int $_cacheLifeTime = 2592000;

    /**
     * Ustawia nazwę
     * @param string $name
     * @return CmsWidgetConfig
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
     * Ustawia klucz
     * @param string $name
     * @return CmsWidgetConfig
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
     * Ustawia maksymalną ilość wystąpień
     * @param integer $maxOccurrence
     * @return CmsWidgetConfig
     */
    public function setMaxOccurrence($maxOccurrence): self
    {
        //walidacja
        if (!is_int($maxOccurrence) || $maxOccurrence < 1) {
            throw new KernelException('Invalid max occurrence');
        }
        $this->_maxOccurrence = $maxOccurrence;
        return $this;
    }

    /**
     * Pobiera maksymalną ilość wystąpień
     * @return integer
     */
    public function getMaxOccurrence(): int
    {
        return $this->_maxOccurrence;
    }

    /**
     * Ustawia najmniejszą ilość wystąpień
     * @param integer $maxOccurrence
     * @return CmsWidgetConfig
     */
    public function setMinOccurrence($minOccurrence): self
    {
        //walidacja
        if (!is_int($minOccurrence) || $minOccurrence < 0) {
            throw new KernelException('Invalid max occurrence');
        }
        $this->_minOccurrence = $minOccurrence;
        return $this;
    }

    /**
     * Pobiera najmniejszą ilość wystąpień
     * @return integer
     */
    public function getMinOccurrence(): int
    {
        return $this->_minOccurrence;
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

    /**
     * Ustawia nazwę klasy kontrolera
     * @param string $controllerClassName
     * @return CmsWidgetConfig
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
}
