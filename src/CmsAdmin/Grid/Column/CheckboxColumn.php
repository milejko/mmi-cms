<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

use Cms\Mvc\ViewHelper\AclAllowed;

/**
 * Klasa Columnu checkbox
 *
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
class CheckboxColumn extends SelectColumn
{
    /**
     * Template komórki checkboxa
     */
    const TEMPLATE_CELL = 'cmsAdmin/grid/cell/checkbox';

    /**
     * Template filtra selecta
     */
    const TEMPLATE_FILTER = 'cmsAdmin/grid/filter/checkbox';

    /**
     * Domyślne opcje dla checkboxa
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setMultioptions([
            1 => $this->view->_('grid.shared.checkbox.on'),
            0 => $this->view->_('grid.shared.checkbox.off'),
        ]);
    }

    /**
     * Ustawia grid
     * @param \CmsAdmin\Grid\Grid $grid
     * @return $this
     */
    public function setGrid(\CmsAdmin\Grid\Grid $grid)
    {
        parent::setGrid($grid);
        //zwrot siebie
        return $this;
    }

    /**
     * Ustawia wyłączenie z edycji
     * @param bool $disabled
     * @return \Mmi\OptionObject
     */
    public function setDisabled($disabled = true)
    {
        return $this->setOption('disabled', (bool)$disabled);
    }

    /**
     * Renderuje pole tekstowe
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        $this->view->_column = $this;
        $this->view->_record = $record;
        $this->view->_value = $this->getValueFromRecord($record);

        //wyłączanie edycji jeśli acl w operacjach (edycji) zabrania
        if ($this->getGrid()->getColumn('_operation_') && !(new AclAllowed($this->view))->aclAllowed($this->getGrid()->getColumn('_operation_')->getOption('editParams'))) {
            $this->setDisabled();
        }
        //obsługa zapisu rekordu
        (new CheckboxRequestHandler($this))->handleRequest();
        //nowy Column select

        return $this->view->renderTemplate(self::TEMPLATE_CELL);
    }

    /**
     * Określa wartość dla zaznaczonego checkboxa (najwyższa)
     * @return integer
     */
    public function getCheckedValue()
    {
        $checked = 0;
        //iteracja po opcjach
        foreach ($this->getMultioptions() as $option => $caption) {
            $checked = ($option >= $checked) ? $option : $checked;
        }
        return $checked;
    }
}
