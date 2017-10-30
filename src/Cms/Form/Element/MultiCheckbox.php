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
 * Element wielokrotny checkbox
 */
class MultiCheckbox extends \Mmi\Form\Element\MultiCheckbox
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

    /**
     * Konstruktor
     * @param string $name
     */
    public function __construct($name)
    {
        $this->addClass('form-control');
        parent::__construct($name);
    }

    /**
     * Render pola
     * @return string
     */
    public function fetchField()
    {
        //wartości pola
        $values = is_array($this->getValue()) ? $this->getValue() : [$this->getValue()];
        $html = '<ul id="' . ($baseId = $this->getId()) . '-list">';
        //filtr url
        $f = new \Mmi\Filter\Url;
        foreach ($this->getMultioptions() as $key => $caption) {
            //nowy checkbox
            $checkbox = new Checkbox($this->getBaseName() . '[]');
            //konfiguracja checkboxa
            $checkbox->setLabel($caption)
                ->setForm($this->_form)
                ->setValue($key)
                ->setId($baseId . '-' . $f->filter($key))
                ->setRenderingOrder(['fetchField']);
            //zaznaczenia wartości
            if (in_array($key, $values)) {
                $checkbox->setChecked();
            }
            //wartość wyłączona
            if (strpos($key, ':disabled') !== false) {
                $checkbox->setValue('')
                    ->setDisabled();
            }
            $html .= '<li ' . ($checkbox->getDisabled() ? 'class="disabled" ' : '') . 'id="' . $checkbox->getId() . '-item' . '">' .
                $checkbox . '</li>';
        }
        return $html . '</ul>';
    }

}
