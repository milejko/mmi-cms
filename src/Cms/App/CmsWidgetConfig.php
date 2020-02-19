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
    private $_name;

    /**
     * Nazwa klasy kontrolera
     * @var string
     */
    private $_controllerClassName;

    /**
     * Minimalna ilość wystąpień
     * @var integer
     */
    private $_minOccurence = 0;

    /**
     * Maksymalna ilość wystąpień
     * @var integer
     */
    private $_maxOccurence = 1000;

    /**
     * Długość bufora
     * @var integer
     */
    private $_cacheLifeTime = 2592000;

    /**
     * Ustawia nazwę
     * @param string $name
     * @return CmsWidgetConfig
     */
    public function setName($name)
    {
        $this->_name = $name;
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
     * Ustawia maksymalną ilość wystąpień
     * @param integer $maxOccurence
     * @return CmsWidgetConfig
     */
    public function setMaxOccurence($maxOccurence)
    {
        //walidacja
        if (!is_int($maxOccurence) || $maxOccurence < 1) {
            throw new KernelException('Invalid max occurence');
        }
        $this->_maxOccurence = $maxOccurence;
        return $this;
    }

    /**
     * Pobiera maksymalną ilość wystąpień
     * @return integer
     */
    public function getMaxOccurence()
    {
        return $this->_maxOccurence;
    }

    /**
     * Ustawia najmniejszą ilość wystąpień
     * @param integer $maxOccurence
     * @return CmsWidgetConfig
     */
    public function setMinOccurence($minOccurence)
    {
        //walidacja
        if (!is_int($minOccurence) || $minOccurence < 0) {
            throw new KernelException('Invalid max occurence');
        }
        $this->_minOccurence = $minOccurence;
        return $this;
    }

    /**
     * Pobiera najmniejszą ilość wystąpień
     * @return integer
     */
    public function getMinOccurence()
    {
        return $this->_minOccurence;
    }

    /**
     * Ustawia czas bufora
     * @param integer $cacheLifeTime
     * @return CmsWidgetConfig
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
     * Ustawia nazwę klasy kontrolera
     * @param string $controllerClassName
     * @return CmsWidgetConfig
     */
    public function setControllerClassName($controllerClassName)
    {
        $this->_controllerClassName = $controllerClassName;
        return $this;
    }

    /**
     * Pobiera nazwę klasy kontrolera
     * @return string
     */
    public function getControllerClassName()
    {
        return $this->_controllerClassName;
    }

}