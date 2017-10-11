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
        $this->setQuery(new \Cms\Orm\CmsCategoryQuery);

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
            ->setLabel('inny adres strony'));

        //title
        $this->addColumn((new Column\TextColumn('title'))
            ->setLabel('meta tytuł'));

        //follow
        $this->addColumn((new Column\CheckboxColumn('follow'))
            ->setLabel('w wyszukiwarkach'));

        //aktywności
        $this->addColumn((new Column\CheckboxColumn('active'))
            ->setLabel('włączona'));

        //operacje
        $this->addColumn((new Column\CustomColumn('operation'))
            ->setLabel('operacje')
            ->setTemplateCode('{$id = $record->id}{if categoryAclAllowed($id)}<a href="{@module=cmsAdmin&controller=category&action=edit&id={$id}@}"><i class="icon-pencil"></i></a>&nbsp;&nbsp;<a href="{@module=cmsAdmin&controller=category&action=delete&id={$id}@}" title="Czy na pewno usunąć" class="confirm"><i class="icon-remove-circle"></i></a>{else}-{/if}')
        );
            
    }

}
