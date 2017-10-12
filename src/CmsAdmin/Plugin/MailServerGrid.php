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
            ->setLabel('adres serwera'));

        //port
        $this->addColumn((new Column\TextColumn('port'))
            ->setLabel('port'));

        //ssl
        $this->addColumn((new Column\TextColumn('ssl'))
            ->setLabel('szyfrowanie'));

        //użytkownik
        $this->addColumn((new Column\TextColumn('username'))
            ->setLabel('użytkownik'));

        //nadawca
        $this->addColumn((new Column\TextColumn('from'))
            ->setLabel('domyślny nadawca'));

        //data dodania
        $this->addColumn((new Column\TextColumn('dateAdd'))
            ->setLabel('data dodania'));

        //data modyfikacji
        $this->addColumn((new Column\TextColumn('dateModify'))
            ->setLabel('data modyfikacji'));

        //operacje
        $this->addColumn(new Column\OperationColumn);
    }

}
