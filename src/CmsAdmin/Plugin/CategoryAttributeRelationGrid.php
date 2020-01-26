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
use Mmi\App\FrontController;

/**
 * Grid atrybutów w szablonie artykułu
 */
class CategoryAttributeRelationGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //domyślne zapytanie
        $this->setQuery(
            (new \Cms\Orm\CmsAttributeRelationQuery)
                ->join('cms_attribute')->on('cms_attribute_id')
                ->joinLeft('cms_attribute_value')->on('cms_attribute_value_id')
                ->whereObject()->equals($this->getOption('object'))
                ->andFieldObjectId()->equals($this->getOption('objectId'))
                ->orderAscOrder()
        );

        //nazwa typu
        $this->addColumn((new Column\TextColumn('cms_attribute.name'))
            ->setLabel('grid.categoryAttributeRelation.cms_attribute.name.label'));

        //kolejność
        $this->addColumn((new Column\TextColumn('order'))
            ->setLabel('grid.categoryAttributeRelation.order.label'));

        //wartość domyślna
        $this->addColumn((new Column\TextColumn('cms_attribute_value.value'))
            ->setLabel('grid.categoryAttributeRelation.cms_attribute_value.value.label'));

        //wymagany
        $this->addColumn((new Column\CheckboxColumn('required'))
            ->setLabel('grid.categoryAttributeRelation.required.label'));

        //unikalny
        $this->addColumn((new Column\CheckboxColumn('unique'))
            ->setLabel('grid.categoryAttributeRelation.unique.label'));

        //operacje
        $this->addColumn((new Column\OperationColumn())
            ->setDeleteParams(['controller' => 'categoryTypeAttribute', 'action' => 'delete', 'id' => '%id%', 'categoryTypeId' => FrontController::getInstance()->getRequest()->id])
            ->setEditParams(['controller' => 'categoryTypeAttribute', 'action' => 'edit', 'id' => '%id%', 'categoryTypeId' => FrontController::getInstance()->getRequest()->id]));
    }

}
