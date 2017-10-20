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
 * Konfiguracja nawigatora atrybutów
 */
class NavPartAttribute extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Atrybuty')
                ->setUri('#')
                ->setModule('cmsAdmin')
                ->setController('attribute')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista atrybutów')
                    ->setModule('cmsAdmin')
                    ->setController('attribute'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj atrybut')
                    ->setModule('cmsAdmin')
                    ->setController('attribute')
                    ->setAction('edit'))
        ;
    }

}
