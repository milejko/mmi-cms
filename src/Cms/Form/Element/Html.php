<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element html
 */
class Html extends \Mmi\Form\Element\ElementAbstract
{

    //szablon początku pola
    CONST TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    CONST TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    CONST TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    CONST TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    CONST TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    public $element = '';

    /**
     * Konstruktor zmienia kolejność renderowania
     * @param string $html
     */
    public function __construct($html)
    {
        parent::__construct(uniqid());
        $this->setLabelPostfix('');
        $this->setIgnore(true);
        $this->setHtml($html);
        $this->setRenderingOrder(['fetchField']);
    }

    /**
     * Ustawia element html
     * @param string $html
     * @return self
     */
    public function setHtml($html)
    {
        $this->element = $html;
        return $this;
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        return $this->element;
    }

}
