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
                ->setIcon('fa-code')
                ->setUri('#')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Zarządzaj drzewem')
                    ->setIcon('fa-tree')
                    ->setModule('cmsAdmin')
                    ->setController('category')
                    ->setAction('tree')
                )
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Edycja strony')
                    ->setDisabled()
                    ->setModule('cmsAdmin')
                    ->setController('category')
                    ->setAction('edit')
                )
                //lista treści
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista treści')
                    ->setModule('cmsAdmin')
                    ->setIcon('fa-table')
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
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('categoryWidgetRelation')
                        ->setAction('add')
                        ->setDisabled(true)
                    )
                )
                //szablony
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Szablony')
                    ->setIcon('fa-table')
                    ->setModule('cmsAdmin')
                    ->setController('categoryType')
                    ->setAction('index'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj szablon')
                    ->setIcon('fa-plus')
                    ->setModule('cmsAdmin')
                    ->setController('categoryType')
                    ->setAction('edit'))
                //widgety
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Widgety')
                    ->setIcon('fa-table')
                    ->setModule('cmsAdmin')
                    ->setController('categoryWidget')
                    ->setAction('index'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj widget')
                    ->setIcon('fa-plus')
                    ->setModule('cmsAdmin')
                    ->setController('categoryWidget')
                    ->setAction('edit'))
                //uprawnienia
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Uprawnienia')
                    ->setIcon('fa-cogs')
                    ->setModule('cmsAdmin')
                    ->setController('categoryAcl')
        );
    }

}
