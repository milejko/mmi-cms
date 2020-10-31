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
 * Klasa Columnu dowolnego
 *
 * @method CustomColumn setTemplateCode($code) dodaje kod
 * @method string getTemplateCode() pobiera kod szablonu
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
class CustomColumn extends ColumnAbstract
{

    /**
     * Ustawia, czy kolumna eksportowalna
     * @param boolean $exporting
     * @return CustomColumn
     */
    public function setExporting($exporting = true)
    {
        return $this->setOption('exporting', $exporting);
    }

    /**
     * Zwraca, czy kolumna eksportowalna
     * @return boolean
     */
    public function getExporting()
    {
        return boolval($this->getOption('exporting'));
    }

    /**
     * Renderuje customowe Columny
     * @param \Mmi\Orm\RecordRo $record
     * @return string
     */
    public function renderCell(\Mmi\Orm\RecordRo $record)
    {
        $this->view->record = $record;
        return $this->view->renderDirectly($this->getTemplateCode());
    }

}
