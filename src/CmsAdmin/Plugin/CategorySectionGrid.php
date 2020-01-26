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
class CategorySectionGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //domyślne zapytanie
        $this->setQuery(
            (new \Cms\Orm\CmsCategorySectionQuery())
                ->whereCategoryTypeId()->equals($this->getOption('typeId'))
                ->orderAscOrder()
        );

        //nazwa sekcji
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('grid.categorySection.name.label'));

        //klucz sekcji
        $this->addColumn((new Column\TextColumn('key'))
            ->setLabel('grid.categorySection.key.label'));

        //kolejność
        $this->addColumn((new Column\TextColumn('order'))
            ->setLabel('grid.categorySection.order.label'));

        //wymagany
        $this->addColumn((new Column\CheckboxColumn('required'))
            ->setLabel('grid.categorySection.required.label'));

        //operacje
        $this->addColumn((new Column\OperationColumn())
            ->setEditParams(['controller' => 'categorySection', 'action' => 'edit', 'id' => '%id%', 'categoryTypeId' => FrontController::getInstance()->getRequest()->id])
            ->setDeleteParams(['controller' => 'categorySection', 'action' => 'delete', 'id' => '%id%', 'categoryTypeId' => FrontController::getInstance()->getRequest()->id]));

    }

}
