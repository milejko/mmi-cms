<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Grid\Column;
use CmsAdmin\Grid\Column\OperationColumn;

/**
 * Grid do prezentacji historycznych wersji danej kategorii
 */
class CategoryGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {
        //query
        $this->setQuery((new CmsCategoryQuery)
                ->whereStatus()->equals(CmsCategoryRecord::STATUS_DELETED)
                ->orQuery((new CmsCategoryQuery())
                        ->whereCmsCategoryOriginalId()->equals(null)
                        ->whereStatus()->equals(CmsCategoryRecord::STATUS_DRAFT)
                )
        );

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('grid.categoryTrash.name.label'));

        //data utworzenia wersji
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('grid.categoryTrash.dateAdd.label'));

        //status
        $this->addColumn((new Column\SelectColumn('status'))
                ->setLabel('grid.categoryTrash.status.label')
                ->setMultioptions([
                    CmsCategoryRecord::STATUS_DELETED => 'grid.categoryTrash.status.option.deleted',
                    CmsCategoryRecord::STATUS_DRAFT => 'grid.categoryTrash.status.option.draft',
                ])
        );

        //operacje
        $this->addColumn((new OperationColumn)
                ->addCustomButton('fa-2 fa-history', ['module' => 'cmsAdmin', 'controller' => 'categoryTrash', 'action' => 'restore', 'id' => '%id%'])
                ->setDeleteParams([])
                ->setEditParams([])
        );
    }
}
