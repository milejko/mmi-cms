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
 * Konfiguracja nawigatora statystyk
 */
class NavPartStat extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
            ->setLabel('menu.stat.container')
            ->setIcon('fa-pie-chart')
            ->setUri('#')
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.stat.index')
                ->setIcon('fa-line-chart')
                ->setModule('cmsAdmin')
                ->setController('stat'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.stat.label')
                ->setIcon('fa-table')
                ->setModule('cmsAdmin')
                ->setController('stat')
                ->setAction('label'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.stat.edit')
                ->setIcon('fa-plus')
                ->setModule('cmsAdmin')
                ->setController('stat')
                ->setAction('edit'));
    }
}
