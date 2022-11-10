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
     * Template filtra selecta
     */
    public const TEMPLATE_FILTER = 'cmsAdmin/grid/filter/select';

    /**
     * Template komórki selecta
     */
    public const TEMPLATE_CELL = 'cmsAdmin/grid/cell/select';

    /**
     * Ustawia opcje selecta
     * @param array $options
     * @return SelectColumn
     */
    public function setMultioptions(array $options = [])
    {
        return $this->setOption('multioptions', $options)
            ->setFilterMethodEquals();
    }

    /**
     * Pobiera opcję po kluczu
     * @param string $key
     * @return string
     */
    public function getMultioptionByKey($key)
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
        $this->view->_column = $this;
        //pusta opcja
        $this->setMultioptions([null => '---'] + $this->getMultioptions());
        //tworzy selecta z template'u
        return $this->view->renderTemplate(self::TEMPLATE_FILTER);
    }

    /**
     * Renderuje pole tekstowe
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        $this->view->_column = $this;
        //zwrot z mapy opcji
        $this->view->_value = $this->getMultioptionByKey($this->getValueFromRecord($record));
        return $this->view->renderTemplate(self::TEMPLATE_CELL);
    }
}
