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
 * Klasa Columnu tekstowego
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
class TextColumn extends ColumnAbstract
{

    /**
     * Template komorki text
     */
    const TEMPLATE_CELL = 'cmsAdmin/grid/cell/text';

    /**
     * Renderuje pole tekstowe
     * @param \Mmi\Orm\RecordRo $record
     * @throws \Mmi\App\KernelException
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        $view = FrontController::getInstance()->getView();
        $view->_value = (new \Mmi\Filter\Escape)->filter($this->getValueFromRecord($record));
        $view->_truncated = (new \Mmi\Filter\Truncate)->filter($view->_value);
        return FrontController::getInstance()->getView()->renderTemplate(static::TEMPLATE_CELL);
    }

}
