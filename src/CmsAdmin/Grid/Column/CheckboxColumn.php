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
use Mmi\App\FrontController;

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
     * Domyślne opcje dla checkboxa
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setMultioptions([
            0 => 'odznaczone',
            1 => 'zaznaczone'
        ]);
        parent::__construct($name);
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
        FrontController::getInstance()->getView()->_column = $this;
        FrontController::getInstance()->getView()->_record = $record;
        FrontController::getInstance()->getView()->_value = $this->getValueFromRecord($record);

        //wyłączanie edycji jeśli acl w operacjach (edycji) zabrania
        if ($this->getGrid()->getColumn('_operation_') && !(new AclAllowed)->aclAllowed($this->getGrid()->getColumn('_operation_')->getOption('editParams'))) {
            $this->setDisabled();
        }
        //obsługa zapisu rekordu
        (new CheckboxRequestHandler($this))->handleRequest();
        //nowy Column select

        return FrontController::getInstance()->getView()->renderTemplate(self::TEMPLATE_CELL);
        return (new \Mmi\Form\Element\Checkbox($this->getFormColumnName()))
            //ustawia wartość na odpowiadającą zaznaczeniu
            ->setValue($this->getCheckedValue())
            ->setId($this->getFormColumnName() . '-' . $record->id)
            //ustawia zaznaczenie
            ->setChecked($this->getCheckedValue() <= $this->getValueFromRecord($record));
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
