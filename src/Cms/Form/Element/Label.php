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
 * Element label
 */
class Label extends \Mmi\Form\Element\ElementAbstract
{

    /**
     * Konstruktor usuwa labelpostfix
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setLabelPostfix('');
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        return '';
    }

}
