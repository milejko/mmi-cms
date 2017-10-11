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
 * Klasa grid loga CMS
 */
class LogGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        //domyślnie posortowane po dacie i czasie
        $this->setQuery((new \Cms\Orm\CmsLogQuery)
            ->orderDescDateTime());

        //data i czas
        $this->addColumn((new Column\TextColumn('dateTime'))
            ->setLabel('data i czas'));

        //operacja
        $this->addColumn((new Column\TextColumn('operation'))
            ->setLabel('operacja'));

        //url
        $this->addColumn((new Column\TextColumn('url'))
            ->setLabel('URL')
            ->setFilterMethodLike());

        //dane
        $this->addColumn((new Column\JsonColumn('data'))
            ->setLabel('dane')
            ->setFilterMethodLike());

        //zasób
        $this->addColumn((new Column\TextColumn('object'))
            ->setLabel('zasób'));

        //id
        $this->addColumn((new Column\TextColumn('objectId'))
            ->setLabel('id zasobu'));

        //sukces
        $this->addColumn((new Column\CheckboxColumn('success'))
            ->setLabel('sukces')
            ->setDisabled());
    }

}
