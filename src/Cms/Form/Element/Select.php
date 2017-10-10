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

    /**
     * Buduje pole
     * @return string
     */
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
