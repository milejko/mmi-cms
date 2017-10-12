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
 * Grid tagów
 */
class TagGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery((new \Cms\Orm\CmsTagQuery)
                ->joinLeft('cms_tag_relation')->on('id', 'cms_tag_id')->groupById()->groupBy('cms_tag_id', 'cms_tag_relation')
        );

        //nazwa taga
        $this->addColumn((new Column\TextColumn('tag'))
            ->setLabel('tag'));

        //operacje
        $this->addColumn((new Column\OperationColumn)
            ->setDeleteParams([])
            ->setDeleteTagParams(['action' => 'delete', 'id' => '%id%']));
    }

}
