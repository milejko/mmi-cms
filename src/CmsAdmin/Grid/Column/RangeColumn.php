<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

/**
 * Klasa Columny range
 *
 * @method array getMultioptions()
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 *
 * @method self setFilterMethodEquals() ustawia metodę filtracji na równość
 * @method self setFilterMethodLike() ustawia metodę filtracji na podobny
 * @method self setFilterMethodSearch() ustawia metodę filtracji na wyszukaj
 * @method self setFilterMethodNull() ustawia metodę filtracji na równe/różne null
 */
class RangeColumn extends TextColumn
{

    /**
     * RangeColumn constructor.
     * @param $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setFilterMethodBetween();
    }

    /**
     * Renderuje filtrację pola
     * @return string
     */
    public function renderFilter()
    {
        $splittedValue = explode(';', $this->_getFilterValue());
        //tworzy Column form selecta, ustawia opcje i wartość filtra
        return (new \Mmi\Form\Element\Hidden($this->getFormColumnName()))
            ->setValue($this->_getFilterValue())
            . '<div class="range"><input style="max-width: 50px;" class="from" data-field="' .$this->getFormColumnName(). '" type="text" value="' . $splittedValue[0] . '" /><input style="max-width: 50px;" class="to" data-field="' .$this->getFormColumnName(). '" type="text" value="' . (isset($splittedValue[1]) ? $splittedValue[1] : '') . '"/></div>';
    }

}
