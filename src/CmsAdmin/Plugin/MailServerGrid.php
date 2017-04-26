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
 * Grid serwerów
 */
class MailServerGrid extends \CmsAdmin\Grid\Grid
{

    public function init()
    {

        $this->setQuery((new \Cms\Orm\CmsMailServerQuery)
                ->orderDescId());

        //adres serwera
        $this->addColumnText('address')
            ->setLabel('adres serwera');

        //port
        $this->addColumnText('port')
            ->setLabel('port');

        //ssl
        $this->addColumnText('ssl')
            ->setLabel('szyfrowanie');

        //użytkownik
        $this->addColumnText('username')
            ->setLabel('użytkownik');

        //nadawca
        $this->addColumnText('from')
            ->setLabel('domyślny nadawca');

        //data dodania
        $this->addColumnText('dateAdd')
            ->setLabel('data dodania');

        //data modyfikacji
        $this->addColumnText('dateModify')
            ->setLabel('data modyfikacji');

        //operacje
        $this->addColumnOperation();
    }

}
