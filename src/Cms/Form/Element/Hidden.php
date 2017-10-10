<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element ukrytego pola formularza
 */
class Hidden extends \Mmi\Form\Element\ElementAbstract
{

    /**
     * Konstruktor zmienia kolejność renderowania
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setRenderingOrder(['fetchBegin', 'fetchField', 'fetchErrors', 'fetchEnd']);
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        return '<input type="hidden" ' . $this->_getHtmlOptions() . '/>';
    }

}
