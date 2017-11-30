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
 * Grid do prezentacji historycznych wersji danej kategorii
 */
class CategoryHistoryGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //query
        $this->setQuery(
                (new \Cms\Orm\CmsCategoryQuery)
                ->whereCmsCategoryOriginalId()->equals($this->getOption('originalId'))
                ->andFieldStatus()->equals(\Cms\Orm\CmsCategoryRecord::STATUS_HISTORY)
                ->orderDescDateAdd()
            );

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('nazwa'));

        //data utworzenia wersji
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('data utworzenia'));

        //operacje
        $this->addColumn((new Column\CustomColumn('operation'))
            ->setLabel('<div style="width: 55px;color: #20a8d8; text-align: center;"><i class="fa fa-2 fa-gears"></i></div>')
            ->setTemplateCode('{if categoryAclAllowed($record->cmsCategoryOriginalId)}<a target="_blank" href="{$record->getUrl()}?versionId={$record->id}" id="category-preview-{$record->id}"><i class="fa fa-2 fa-eye"></i></a>&nbsp;&nbsp;<a href="{@module=cmsAdmin&controller=category&action=edit&id={$record->id}@}" id="category-restore-{$record->id}"><i class="fa fa-2 fa-history"></i></a>{else}-{/if}')
        );
            
    }

}
