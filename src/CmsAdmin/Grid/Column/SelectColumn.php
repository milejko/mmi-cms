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
 * Klasa Columnu select
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
class SelectColumn extends ColumnAbstract
{

    /**
     * Ustawia opcje selecta
     * @param array $options
     * @return SelectColumn
     */
    public function setMultioptions(array $options = [])
    {
        return $this->setOption('multioptions', $options);
    }

    /**
     * Pobiera opcję po kluczu
     * @param string $key
     * @return string
     */
    public function getMultiOptionByKey($key)
    {
        $multioptions = $this->getMultioptions();
        //wyszukiwanie w multiopcjach
        return isset($multioptions[$key]) ? $multioptions[$key] : $key;
    }

    /**
     * Renderuje filtrację pola
     * @return string
     */
    public function renderFilter()
    {
        //tworzy Column form selecta, ustawia opcje i wartość filtra
        return (new \Mmi\Form\Element\Select($this->getFormColumnName()))
                ->setMultioptions([null => '---'] + $this->getMultioptions())
                ->setValue($this->_getFilterValue());
    }

    /**
     * Renderuje pole tekstowe
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        //brak pola
        if (!$this->_fieldInRecord()) {
            return '?';
        }
        //zwrot z mapy opcji
        return $this->getMultiOptionByKey($this->getValueFromRecord($record));
    }

}
