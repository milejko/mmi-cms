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
 * Grid komentarzy
 */
class CommentGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery((new \Cms\Orm\CmsCommentQuery));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('data dodania'));

        //komentarz
        $this->addColumn((new Column\TextColumn('text'))
            ->setLabel('komentarz'));

        //podpis
        $this->addColumn((new Column\TextColumn('signature'))
            ->setLabel('podpis'));

        //zasób
        $this->addColumn((new Column\TextColumn('object'))
            ->setLabel('zasób'));

        //id zasobu
        $this->addColumn((new Column\TextColumn('objectId'))
            ->setLabel('id zasobu'));

        //operacje bez edycji
        $this->addColumnOperation()
            ->setEditParams([]);
    }

}
