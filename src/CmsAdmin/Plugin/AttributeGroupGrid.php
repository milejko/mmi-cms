<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

use CmsAdmin\Grid\Column;

/**
 * Grid grup atrybutów
 */
class AttributeGroupGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(new \Cms\Orm\CmsAttributeGroupQuery);

        //nazwa taga
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('nazwa'));

        //klasa pola
        $this->addColumn((new Column\TextColumn('description'))
            ->setLabel('opis'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
