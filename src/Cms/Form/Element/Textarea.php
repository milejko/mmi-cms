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
 * Element textarea
 */
class Textarea extends \Mmi\Form\Element\Textarea
{

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $value = (new \Mmi\Filter\Input)->filter($this->getValue());
        $this->unsetOption('value');
        return '<textarea ' . $this->_getHtmlOptions() . '>' . $value . '</textarea>';
    }

}
