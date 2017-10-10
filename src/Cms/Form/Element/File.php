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
 * Element plikowy
 */
class File extends \Mmi\Form\Element\File
{

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        return '<input type="file" ' . $this->_getHtmlOptions() . '/>';
    }

}
