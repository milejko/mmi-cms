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
 * Konfiguracja nawigatora użytkowników
 */
class NavPartAuth extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
            ->setLabel('menu.auth.container')
            ->setIcon('fa-users')
            ->setUri('#')
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.auth.index')
                ->setIcon('fa-table')
                ->setModule('cmsAdmin')
                ->setController('auth'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.auth.edit')
                ->setIcon('fa-user-plus')
                ->setModule('cmsAdmin')
                ->setController('auth')
                ->setAction('edit'))
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.acl')
                    ->setIcon('fa-key')
                    ->setModule('cmsAdmin')
                    ->setController('acl')
            );
    }
}
