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
 * 
 * Metody add
 * @method self addClass($className) dodaje klasę HTML
 * @method self addFilter(\Mmi\Filter\FilterAbstract $filter) dodaje filtr
 * @method self addError($error) dodaje błąd
 * 
 * Settery
 * @method self setName($name) ustawia nazwę
 * @method self setValue($value) ustawia wartość
 * @method self setId($id) ustawia identyfikator
 * @method self setPlaceholder($placeholder) ustawia placeholder pola
 * @method self setDescription($description) ustawia opis
 * @method self setIgnore($ignore = true) ustawia ignorowanie
 * @method self setDisabled($disabled = true) ustawia wyłączone
 * @method self setReadOnly($readOnly = true) ustawia tylko do odczytu
 * @method self setLabel($label) ustawia labelkę
 * @method self setRequiredAsterisk($asterisk = '*') ustawia znak gwiazdki
 * @method self setRequired($required = true) ustawia wymagalność
 * @method self setLabelPostfix($labelPostfix) ustawia postfix labelki
 * @method self setForm(\Mmi\Form\Form $form) ustawia formularz
 * 
 * @method self setMultioptions(array $multioptions = []) ustawia multiopcje
 * 
 * Gettery
 * @method array getMultioptions() pobiera multiopcje
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
