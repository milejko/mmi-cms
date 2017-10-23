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
 * Konfiguracja nawigatora CRON
 */
class NavPartCron extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Cron')
                ->setIcon('fa-server')
                ->setUri('#')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista zadań')
                    ->setIcon('fa-list')
                    ->setModule('cmsAdmin')
                    ->setModule('cmsAdmin')
                    ->setController('cron'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj zadanie')
                    ->setIcon('fa-plus')
                    ->setModule('cmsAdmin')
                    ->setController('cron')
                    ->setAction('edit')
        );
    }

}
