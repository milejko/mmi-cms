<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App;

/**
 * Konfiguracja nawigatora
 */
class CmsNavigationConfig extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Pobiera menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
            ->setId('admin-menu')
            ->setLabel('menu.index.index')
            ->setModule('cmsAdmin')
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setModule('cmsAdmin')
                ->setDisabled()
                ->setAction('password')
                ->setLabel('menu.index.password'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setModule('cmsAdmin')
                ->setAction('login')
                ->setIcon('fa-unlock-alt')
                ->setLabel('menu.index.login')
                ->setDisabled())
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.container')
                    ->setIcon('fa-cog')
                    ->setUri('#')
                    ->addChild(NavPart\NavPartCategory::getMenu())
                    ->addChild(NavPart\NavPartText::getMenu())
                    ->addChild(NavPart\NavPartMail::getMenu())
                    ->addChild(NavPart\NavPartStat::getMenu())
                    ->addChild(NavPart\NavPartContact::getMenu())
                    ->addChild(NavPart\NavPartAuth::getMenu())
                    ->addChild(NavPart\NavPartSystem::getMenu())
            );
    }
}
