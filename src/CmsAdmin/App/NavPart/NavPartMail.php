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
            ->setLabel('menu.mail.container')
            ->setIcon('fa-envelope-o')
            ->setUri('#')
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.mail.index')
                ->setIcon('fa-table')
                ->setModule('cmsAdmin')
                ->setController('mail'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.mail.preview')
                ->setDisabled()
                ->setModule('cmsAdmin')
                ->setController('mail')
                ->setAction('preview'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.mail.send')
                ->setIcon('fa-paper-plane')
                ->setModule('cmsAdmin')
                ->setController('mail')
                ->setAction('send'))
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.mailDefinition.container')
                    ->setIcon('fa-clone')
                    ->setUri('#')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('menu.mailDefinition.index')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('mailDefinition'))
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('menu.mailDefinition.edit')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('mailDefinition')
                        ->setAction('edit'))
            )
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.mailServer.container')
                    ->setIcon('fa-server')
                    ->setUri('#')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('menu.mailServer.index')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('mailServer'))
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('menu.mailServer.edit')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('mailServer')
                        ->setAction('edit'))
            );
    }
}
