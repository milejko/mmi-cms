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
 * Element select
 */
class Select extends \Mmi\Form\Element\Select
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

    public function fetchField()
    {
        $value = $this->getValue();
        if ($this->issetOption('multiple')) {
            $this->setName($this->getName() . '[]');
        }
        unset($this->_options['value']);
        //nagłówek selecta
        $html = '<select ' . $this->_getHtmlOptions() . '>';
        //generowanie opcji
        foreach (($this->getMultioptions() ? $this->getMultioptions() : []) as $key => $caption) {
            $disabled = '';
            //disabled
            if (strpos($key, ':disabled') !== false && !is_array($caption)) {
                $key = '';
                $disabled = ' disabled="disabled"';
            }
            //jeśli wystąpi zagnieżdżenie - generowanie grupy opcji
            if (is_array($caption)) {
                $html .= '<optgroup label="' . $key . '">';
                foreach ($caption as $k => $c) {
                    $html .= '<option value="' . $k . '" ' . $this->_calculateSelected($k, $value) . $disabled . '>' . $c . '</option>';
                }
                $html .= '</optgroup>';
                continue;
            }
            //dodawanie pojedynczej opcji
            $html .= '<option value="' . $key . '"' . $this->_calculateSelected($key, $value) . $disabled . '>' . $caption . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
