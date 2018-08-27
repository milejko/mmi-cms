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
 * Grid tekstów stałych
 */
class TextGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery(\Cms\Orm\CmsTextQuery::lang()
                ->orderAscKey());

        //klucz
        $this->addColumn((new Column\TextColumn('key'))
            ->setLabel('klucz'));

        //język
        $this->addColumn((new Column\TextColumn('lang'))
            ->setLabel('język'));

        //zawartość
        $this->addColumn((new Column\TextColumn('content'))
            ->setLabel('treść'));

        //data modyfikacji
        $this->addColumn((new Column\DateTimeColumn('dateModify'))
            ->setLabel('data modyfikacji'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
