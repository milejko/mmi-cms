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
 * Grid kategorii
 */
class CategoryGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //query
        $this->setQuery((new \Cms\Orm\CmsCategoryQuery)->whereStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE));

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('grid.category.name.label'));

        //uri
        $this->addColumn((new Column\SelectColumn('uri'))
            ->setMultioptions((new \Cms\Orm\CmsCategoryQuery)->orderAscUri()->findPairs('uri', 'uri'))
            ->setFilterMethodLike()
            ->setLabel('grid.category.uri.label'));
        
        //uri
        $this->addColumn((new Column\TextColumn('customUri'))
            ->setLabel('grid.category.customUri.label'));

        //title
        $this->addColumn((new Column\TextColumn('title'))
            ->setLabel('grid.category.title.label'));

        //aktywności
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('grid.category.active.label'));

        //operacje
        $this->addColumn((new Column\CustomColumn('operation'))
            ->setLabel('grid.shared.operation.label')
            ->setTemplateCode('{$id = $record->id}{if categoryAclAllowed($id)}<a href="{@module=cmsAdmin&controller=category&action=edit&id={$id}@}" id="category-edit-{$id}"><i class="fa fa-2 fa-edit"></i></a>{else}-{/if}')
        );
            
    }

}
