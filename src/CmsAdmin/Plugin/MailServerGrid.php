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
 * Grid serwerów
 */
class MailServerGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        $this->setQuery((new \Cms\Orm\CmsMailServerQuery)
                ->orderDescId());

        //adres serwera
        $this->addColumn((new Column\TextColumn('address'))
            ->setLabel('grid.mailServer.address.label'));

        //port
        $this->addColumn((new Column\TextColumn('port'))
            ->setLabel('grid.mailServer.port.label'));

        //ssl
        $this->addColumn((new Column\TextColumn('ssl'))
            ->setLabel('grid.mailServer.ssl.label'));

        //użytkownik
        $this->addColumn((new Column\TextColumn('username'))
            ->setLabel('grid.mailServer.username.label'));

        //nadawca
        $this->addColumn((new Column\TextColumn('from'))
            ->setLabel('grid.mailServer.from.label'));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('grid.mailServer.dateAdd.label'));

        //data modyfikacji
        $this->addColumn((new Column\TextColumn('dateModify'))
            ->setLabel('grid.mailServer.dateModify.label'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
