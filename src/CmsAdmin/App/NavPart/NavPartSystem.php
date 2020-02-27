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
 * Konfiguracja nawigatora tekstów stałych
 */
class NavPartSystem extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
            ->setLabel('menu.system.container')
            ->setIcon('fa-cogs')
            ->setUri('#')
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.cron.container')
                ->setIcon('fa-calendar')
                ->setUri('#')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.cron.index')
                    ->setIcon('fa-table')
                    ->setModule('cmsAdmin')
                    ->setController('cron')
                )
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('menu.cron.edit')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('cron')
                        ->setAction('edit')
                )
            )
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.file.index')
                ->setIcon('fa-file')
                ->setModule('cmsAdmin')
                ->setController('file')
            )
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.connector.index')
                ->setIcon('fa-cloud-download')
                ->setModule('cmsAdmin')
                ->setController('connector')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.connector.files')
                    ->setModule('cmsAdmin')
                    ->setController('connector')
                    ->setAction('files')
                    ->setDisabled()
                )
            )
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.config')
                ->setIcon('fa-list')
                ->setModule('cmsAdmin')
                ->setController('config')
            )
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.cache')
                ->setModule('cmsAdmin')
                ->setIcon('fa-trash')
                ->setController('cache')
            );
    }
}
