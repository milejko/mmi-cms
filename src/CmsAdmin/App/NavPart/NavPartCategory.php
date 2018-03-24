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
                ->setIcon('fa-image')
                ->setUri('#')
                ->setModule('cmsAdmin')
                ->setController('category')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Zarządzaj menu')
                    ->setIcon('fa-compass')
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
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setTitle('Konfiguracja widgeta')
                        ->setModule('cmsAdmin')
                        ->setController('categoryWidgetRelation')
                        ->setAction('config')
                        ->setDisabled()
                    )
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setTitle('Nowy widget')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('categoryWidgetRelation')
                        ->setAction('add')
                        ->setDisabled()
                    )
                )
                //uprawnienia
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Uprawnienia')
                    ->setIcon('fa-key')
                    ->setModule('cmsAdmin')
                    ->setController('categoryAcl')
        );
    }

}
