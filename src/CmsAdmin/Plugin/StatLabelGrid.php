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
 * Grid opisu statystyk
 */
class StatLabelGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(new \Cms\Orm\CmsStatLabelQuery);

        //obiekt
        $this->addColumn((new Column\TextColumn('object'))
            ->setLabel('klucz'));

        //nazwa
        $this->addColumn((new Column\TextColumn('label'))
            ->setLabel('nazwa statystyki'));

        //opis
        $this->addColumn((new Column\TextColumn('description'))
            ->setLabel('opis'));

        //operacje bez usuwania
        $this->addColumn((new Column\OperationColumn)
            ->setDeleteParams([]));
    }

}
