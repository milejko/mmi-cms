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
 * Grid atrybutów
 */
class AttributeGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(new \Cms\Orm\CmsAttributeQuery);

        //nazwa atrybutu
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('grid.attribute.label.name'));

        //klucz atrybutu
        $this->addColumn((new Column\TextColumn('key'))
            ->setLabel('grid.attribute.label.key'));

        //opis
        $this->addColumn((new Column\TextColumn('description'))
            ->setLabel('grid.attribute.label.description'));

        //klasa pola
        $this->addColumn((new Column\SelectColumn('cmsAttributeTypeId'))
            ->setLabel('grid.attribute.label.type')
            ->setMultioptions((new \Cms\Orm\CmsAttributeTypeQuery)
                ->orderAscName()
                ->findPairs('id', 'name')));

        //waga
        //$this->addColumn((new Column\TextColumn('indexWeight'))
        //	->setLabel('waga w indeksie'));
        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
