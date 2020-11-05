<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

use Mmi\Navigation\NavigationConfigAbstract;

/**
 * Konfiguracja nawigatora tekstów stałych
 */
class NavPartText extends NavigationConfigAbstract
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
            ->setLabel('menu.text.container')
            ->setIcon('fa-align-left')
            ->setUri('#')
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.text.index')
                ->setIcon('fa-table')
                ->setModule('cmsAdmin')
                ->setController('text'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.text.edit')
                ->setIcon('fa-plus')
                ->setModule('cmsAdmin')
                ->setController('text')
                ->setAction('edit'));
    }
}
