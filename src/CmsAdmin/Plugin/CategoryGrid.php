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
            ->setLabel('nazwa'));

        //uri
        $this->addColumn((new Column\SelectColumn('uri'))
            ->setMultioptions((new \Cms\Orm\CmsCategoryQuery)->orderAscUri()->findPairs('uri', 'uri'))
            ->setFilterMethodLike()
            ->setLabel('okruszki'));

        //uri
        $this->addColumn((new Column\TextColumn('customUri'))
            ->setLabel('inny adres'));

        //title
        $this->addColumn((new Column\TextColumn('title'))
            ->setLabel('meta tytuł'));

        //follow
        $this->addColumn((new Column\CheckboxColumn('follow'))
            ->setLabel('wyszukiwarki'));

        //aktywności
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('włączona'));

        //operacje
        $this->addColumn((new Column\CustomColumn('operation'))
            ->setLabel('<div style="width: 55px;color: #20a8d8; text-align: center;"><i class="fa fa-2 fa-gears"></i></div>')
            ->setTemplateCode('{$id = $record->id}{if categoryAclAllowed($id)}<a href="{@module=cmsAdmin&controller=category&action=edit&id={$id}@}" id="category-edit-{$id}"><i class="fa fa-2 fa-edit"></i></a>&nbsp;&nbsp;<a href="{@module=cmsAdmin&controller=category&action=delete&id={$id}@}" title="Czy na pewno usunąć" class="confirm" id="category-delete-{$id}"><i class="fa fa-2 fa-trash-o"></i></a>{else}-{/if}')
        );
            
    }

}
