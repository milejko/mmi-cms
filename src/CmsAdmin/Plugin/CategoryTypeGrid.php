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
 * Grid typów artykułu
 */
class CategoryTypeGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //domyślne zapytanie
        $this->setQuery(new \Cms\Orm\CmsCategoryTypeQuery);

        //nazwa typu
        $this->addColumn((new Column\TextColumn('name'))
            ->setLabel('nazwa'));

        //klucz
        $this->addColumn((new Column\TextColumn('key'))
            ->setLabel('klucz'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
