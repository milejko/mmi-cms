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
 * Konfiguracja nawigatora kategorii
 */
class NavPartCategory extends NavigationConfigAbstract
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
            ->setLabel('menu.category.container')
            ->setIcon('fa-image')
            ->setUri('#')
            ->setModule('cmsAdmin')
            ->setController('category')
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.category.tree')
                    ->setIcon('fa-pencil')
                    ->setModule('cmsAdmin')
                    ->setController('category')
                    ->setAction('tree')
                    ->addChild(
                        (new \Mmi\Navigation\NavigationConfigElement)
                            ->setLabel('menu.category.edit')
                            ->setDisabled()
                            ->setModule('cmsAdmin')
                            ->setController('category')
                            ->setAction('edit')
                            ->addChild(
                                (new \Mmi\Navigation\NavigationConfigElement)
                                    ->setLabel('menu.category.edit.widget')
                                    ->setDisabled()
                                    ->setModule('cmsAdmin')
                                    ->setController('categoryWidgetRelation')
                                    ->setAction('add')
                            )
                            ->addChild(
                                (new \Mmi\Navigation\NavigationConfigElement)
                                    ->setLabel('menu.category.edit.widget')
                                    ->setDisabled()
                                    ->setModule('cmsAdmin')
                                    ->setController('categoryWidgetRelation')
                                    ->setAction('edit')
                            )
                    )
            )
            //lista treści
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.category.index')
                    ->setModule('cmsAdmin')
                    ->setIcon('fa-table')
                    ->setController('category')
            )
            //uprawnienia
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.categoryAcl')
                    ->setIcon('fa-key')
                    ->setModule('cmsAdmin')
                    ->setController('categoryAcl')
            );
    }
}
