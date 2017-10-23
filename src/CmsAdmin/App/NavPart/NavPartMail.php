<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

/**
 * Konfiguracja nawigatora mail
 */
class NavPartMail extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Poczta')
                ->setIcon('fa-envelope-o')
                ->setUri('#')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Kolejka mailowa')
                    ->setIcon('fa-th-list')
                    ->setModule('cmsAdmin')
                    ->setController('mail'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Wyślij z kolejki')
                    ->setIcon('fa-paper-plane')
                    ->setModule('cmsAdmin')
                    ->setController('mail')
                    ->setAction('send'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista szablonów')
                    ->setIcon('fa-list')
                    ->setModule('cmsAdmin')
                    ->setController('mailDefinition'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj szablon')
                    ->setIcon('fa-plus')
                    ->setModule('cmsAdmin')
                    ->setController('mailDefinition')
                    ->setAction('edit'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista serwerów')
                    ->setIcon('fa-list')
                    ->setModule('cmsAdmin')
                    ->setController('mailServer'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj serwer')
                    ->setIcon('fa-plus')
                    ->setModule('cmsAdmin')
                    ->setController('mailServer')
                    ->setAction('edit'));
    }

}
