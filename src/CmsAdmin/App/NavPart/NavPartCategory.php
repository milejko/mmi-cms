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
 * Konfiguracja nawigatora kategorii
 */
class NavPartCategory extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Treść')
                ->setModule('cmsAdmin')
                ->setController('category')
                ->setAction('edit')
                //lista treści
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista treści')
                    ->setModule('cmsAdmin')
                    ->setController('category')
                    ->setAction('index')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setTitle('Konfiguracja widgeta')
                        ->setModule('cmsAdmin')
                        ->setController('categoryWidgetRelation')
                        ->setAction('config')
                        ->setDisabled(true)
                    )
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setTitle('Nowy widget')
                        ->setModule('cmsAdmin')
                        ->setController('categoryWidgetRelation')
                        ->setAction('add')
                        ->setDisabled(true)
                    )
                )
                //szablony
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Szablony')
                    ->setModule('cmsAdmin')
                    ->setController('categoryType')
                    ->setAction('index')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Dodaj')
                        ->setModule('cmsAdmin')
                        ->setController('categoryType')
                        ->setAction('edit'))
                )
                //widgety
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Widgety')
                    ->setModule('cmsAdmin')
                    ->setController('categoryWidget')
                    ->setAction('index')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Dodaj')
                        ->setModule('cmsAdmin')
                        ->setController('categoryWidget')
                        ->setAction('edit')))
                //uprawnienia
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Uprawnienia')
                    ->setModule('cmsAdmin')
                    ->setController('categoryAcl')
                )
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Wyczyść bufor')
                    ->setModule('cmsAdmin')
                    ->setController('cache')
                );
    }

}
