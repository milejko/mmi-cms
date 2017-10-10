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
 * Element pole tekstowe
 */
class Text extends \Mmi\Form\Element\ElementAbstract
{

    /**
     * Rendering pola tekstowego
     * @return string
     */
    public function fetchField()
    {
        $this->setValue((new \Mmi\Filter\Input)->filter($this->getValue()));
        return '<input type="text" ' . $this->_getHtmlOptions() . '/>';
    }

}
