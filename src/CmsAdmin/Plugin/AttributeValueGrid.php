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
 * Grid wartości atrybutów
 */
class AttributeValueGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(
            (new \Cms\Orm\CmsAttributeValueQuery)
                ->orderAscOrder()
                ->orderAscLabel()
                ->orderAscValue()
        );
        $attributeQuery = new \Cms\Orm\CmsAttributeQuery;

        //zapytanie filtrowane ID atrybutu
        if ($this->getOption('id')) {
            $this->getQuery()
                ->whereCmsAttributeId()->equals($this->getOption('id'));
            $attributeQuery->whereId()->equals($this->getOption('id'));
        }

        //wartość
        $this->addColumn((new Column\TextColumn('value'))
            ->setLabel('grid.attributeValue.value.label'));

        //etykieta
        $this->addColumn((new Column\TextColumn('label'))
            ->setLabel('grid.attributeValue.label.label'));
        
        //kolejność
        $this->addColumn((new Column\TextColumn('order'))
            ->setLabel('grid.attributeValue.order.label'));

        //operacje
        $this->addColumn((new Column\OperationColumn())->setEditParams(['module' => 'cmsAdmin', 'controller' => 'attribute', 'action' => 'edit', 'id' => '%cmsAttributeId%', 'valueId' => '%id%'])
            ->setDeleteParams(['module' => 'cmsAdmin', 'controller' => 'attributeValue', 'action' => 'delete', 'id' => '%id%']));
    }

}
