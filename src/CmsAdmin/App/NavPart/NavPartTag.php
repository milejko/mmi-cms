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
 * Konfiguracja nawigatora tagów
 */
class NavPartTag extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Tagi')
                ->setUri('#')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista tagów')
                    ->setModule('cmsAdmin')
                    ->setController('tag'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj tag')
                    ->setModule('cmsAdmin')
                    ->setController('tag')
                    ->setAction('edit'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Relacje tagów')
                    ->setModule('cmsAdmin')
                    ->setController('tagRelation'))
        ;
    }

}
