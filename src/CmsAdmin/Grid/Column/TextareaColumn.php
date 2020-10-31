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
class TextareaColumn extends ColumnAbstract
{
    /**
     * Template filtra textarea
     */
    const TEMPLATE_FILTER = 'cmsAdmin/grid/filter/text';

    /**
     * Template komórki textarea
     */
    const TEMPLATE_CELL = 'cmsAdmin/grid/cell/textarea';

    /**
     * Renderuje pole tekstowe, długie
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        $value = (new \Mmi\Filter\Escape)->filter($this->getValueFromRecord($record));
        $this->view->_value = $value;
        //obcinanie tekstu
        if ('' == $truncated = (new \Mmi\Filter\Truncate)->setLength(200)->filter($value)) {
            return;
        }
        $this->view->_truncated = $truncated;

        return $this->view->renderTemplate(self::TEMPLATE_CELL);
    }

}
