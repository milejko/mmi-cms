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
                ->setModule('cmsAdmin')
                ->setController('mail')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista maili')
                    ->setIcon('fa-table')
                    ->setModule('cmsAdmin')
                    ->setController('mail'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Podgląd wiadomości')
                    ->setDisabled()
                    ->setModule('cmsAdmin')
                    ->setController('mail')
                    ->setAction('preview'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Wyślij z kolejki')
                    ->setIcon('fa-paper-plane')
                    ->setModule('cmsAdmin')
                    ->setController('mail')
                    ->setAction('send'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Szablony')
                    ->setIcon('fa-clone')
                    ->setModule('cmsAdmin')
                    ->setController('mailDefinition')
                    ->setUri('#')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Lista szablonów')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('mailDefinition'))
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Dodaj szablon')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('mailDefinition')
                        ->setAction('edit'))
                )
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista serwerów')
                    ->setIcon('fa-server')
                    ->setModule('cmsAdmin')
                    ->setController('mailServer')
                    ->setUri('#')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Lista serwerów')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('mailServer'))
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Dodaj serwer')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('mailServer')
                        ->setAction('edit'))
                );
    }

}
