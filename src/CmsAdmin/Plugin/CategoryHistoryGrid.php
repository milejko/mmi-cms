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
                ->joinLeft('cms_auth')->on('cms_auth_id')
                ->whereQuery((new \Cms\Orm\CmsCategoryQuery)
                    ->whereCmsCategoryOriginalId()->equals($this->getOption('originalId'))
                    ->orFieldId()->equals($this->getOption('originalId'))
                )
                ->whereStatus()->notEquals(\Cms\Orm\CmsCategoryRecord::STATUS_DRAFT)
                ->orderDescDateAdd()
                //bieżąca ma niższy numer niż archiwum
                ->orderAscId()
        );

        //nazwa
        $this->addColumn((new Column\TextColumn('name'))
                ->setLabel('nazwa'));

        $this->addColumn((new Column\SelectColumn('cms_auth.username'))
                ->setMultioptions((new \Cms\Orm\CmsCategoryQuery)->join('cms_auth')->on('cms_auth_id')->findUnique('cms_auth.username'))
                ->setLabel('użytkownik'));

        //data utworzenia wersji
        $this->addColumn((new Column\TextColumn('dateAdd'))
                ->setLabel('data utworzenia'));

        //status
        $this->addColumn((new Column\SelectColumn('status'))
                ->setLabel('wersja')
                ->setMultioptions([
                    \Cms\Orm\CmsCategoryRecord::STATUS_DRAFT => 'robocza',
                    \Cms\Orm\CmsCategoryRecord::STATUS_ACTIVE => 'bieżąca',
                    \Cms\Orm\CmsCategoryRecord::STATUS_HISTORY => 'archiwalna',
                ])
        );

        //operacje
        $this->addColumn((new Column\CustomColumn('operation'))
                ->setLabel('<div style="width: 55px;color: #20a8d8; text-align: center;"><i class="fa fa-2 fa-gears"></i></div>')
                ->setTemplateCode('{if categoryAclAllowed($record->cmsCategoryOriginalId ? $record->cmsCategoryOriginalId : $record->id)}<a target="_blank" href="{$record->getUrl()}{if $record->cmsCategoryOriginalId}?originalId={$record->cmsCategoryOriginalId}&versionId={$record->id}{/if}" id="category-preview-{$record->id}"><i class="fa fa-2 fa-eye"></i></a>&nbsp;&nbsp;<a title="{if $record->status == 10}utwórz kopię roboczą{elseif $record->status == 20}przywróć{else}kontynuuj edycję{/if}" href="{@module=cmsAdmin&controller=category&action=edit&id={$record->id}@}{if $record->cmsCategoryOriginalId}&originalId={$record->cmsCategoryOriginalId}{else}&force=1{/if}" id="category-restore-{$record->id}"><i class="fa fa-2 {if $record->status == 10}fa-clone{elseif $record->status == 20}fa-history{else}fa-pencil{/if}"></i></a>{else}-{/if}')
        );
    }
    
}
