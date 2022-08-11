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
use CmsAdmin\Grid\Column\TextColumn;

/**
 * Grid tekstów stałych
 */
class TextGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //zapytanie
        $this->setQuery((new \Cms\Orm\CmsTextQuery)
                ->orderAscKey());

        $this->addColumn((new TextColumn('lang')));

        //klucz
        $this->addColumn((new TextColumn('key'))
            ->setLabel('grid.text.key.label'));

        //język
        $this->addColumn((new TextColumn('lang'))
            ->setLabel('grid.text.lang.label'));

        //zawartość
        $this->addColumn((new TextColumn('content'))
            ->setLabel('grid.text.content.label'));

        //data modyfikacji
        $this->addColumn((new Column\DateTimeColumn('dateModify'))
            ->setLabel('grid.text.dateModify.label'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
