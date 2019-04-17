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
class TagRelationGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery((new \Cms\Orm\CmsTagRelationQuery)
                ->join('cms_tag')->on('cms_tag_id')
        );

        //nazwa taga
        $this->addColumn((new Column\TextColumn('cms_tag.tag'))
            ->setLabel('grid.tagRelation.cms_tag.tag.label'));

        //obiekt
        $this->addColumn((new Column\TextColumn('object'))
            ->setLabel('grid.tagRelation.object.label'));

        //id obiektu
        $this->addColumn((new Column\TextColumn('objectId'))
            ->setLabel('grid.tagRelation.objectId.label'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
