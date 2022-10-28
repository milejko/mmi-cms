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
 * Klasa Columnu tekstowego
 *
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 *
 * @method self setFilterMethodBetween() ustawia metodę filtracji na pomiędzy
 */
class RangeColumn extends TextColumn
{
    /**
     * Template filtra datetime
     */
    public const TEMPLATE_FILTER = 'cmsAdmin/grid/filter/range';

    /**
     * Domyślne opcje dla checkboxa
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setFilterMethodBetween();
        parent::__construct($name);
    }
}
