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
        $this->addColumn((new Column\DateTimeColumn('dateTime'))
            ->setLabel('grid.log.dateTime.label'));

        //operacja
        $this->addColumn((new Column\TextColumn('operation'))
            ->setLabel('grid.log.operation.label'));

        //url
        $this->addColumn((new Column\TextColumn('url'))
            ->setLabel('grid.log.url.label')
            ->setFilterMethodLike());

        //dane
        $this->addColumn((new Column\JsonColumn('data'))
            ->setLabel('grid.log.data.label')
            ->setFilterMethodLike());

        //zasób
        $this->addColumn((new Column\TextColumn('object'))
            ->setLabel('grid.log.object.label'));

        //id
        $this->addColumn((new Column\TextColumn('objectId'))
            ->setLabel('grid.log.objectId.label'));

        //sukces
        $this->addColumn((new Column\CheckboxColumn('success'))
            ->setLabel('grid.log.success.label')
            ->setDisabled());
    }

}
