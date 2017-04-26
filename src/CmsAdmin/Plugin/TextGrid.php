<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

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
        $this->addColumnText('key')
            ->setLabel('klucz');

        //język
        $this->addColumnText('lang')
            ->setLabel('język');

        //zawartość
        $this->addColumnText('content')
            ->setLabel('treść');

        //data modyfikacji
        $this->addColumnText('dateModify')
            ->setLabel('data modyfikacji');

        //operacje
        $this->addColumnOperation();
    }

}
