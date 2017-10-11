<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

use Mmi\App\FrontController;

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
    const TEMPLATE_FILTER = 'cmsAdmin/grid/filter/select';

    /**
     * Template komórki selecta
     */
    const TEMPLATE_CELL = 'cmsAdmin/grid/cell/select';

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
        FrontController::getInstance()->getView()->_column = $this;
        //pusta opcja
        $this->setMultioptions(array_merge([null => '---'], $this->getMultioptions()));
        //tworzy selecta z template'u
        return FrontController::getInstance()->getView()->renderTemplate(self::TEMPLATE_FILTER);
    }

    /**
     * Renderuje pole tekstowe
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        FrontController::getInstance()->getView()->_column = $this;
        //zwrot z mapy opcji
        FrontController::getInstance()->getView()->_value = $this->getMultioptionByKey($this->getValueFromRecord($record));
        return FrontController::getInstance()->getView()->renderTemplate(self::TEMPLATE_CELL);
    }

}
