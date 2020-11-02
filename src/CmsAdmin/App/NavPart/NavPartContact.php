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
 * Konfiguracja nawigatora kontaktu
 */
class NavPartContact extends NavigationConfigAbstract
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
            ->setLabel('menu.contact.container')
            ->setIcon('fa-comments')
            ->setUri('#')
            ->addChild(
                (new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('menu.contact.index')
                    ->setIcon('fa-table')
                    ->setModule('cmsAdmin')
                    ->setController('contact')
            )
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.contact.edit')
                ->setDisabled()
                ->setModule('cmsAdmin')
                ->setController('contact')
                ->setAction('edit'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.contact.subject')
                ->setIcon('fa-table')
                ->setModule('cmsAdmin')
                ->setController('contact')
                ->setAction('subject'))
            ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('menu.contact.editSubject')
                ->setIcon('fa-plus')
                ->setModule('cmsAdmin')
                ->setController('contact')
                ->setAction('editSubject'));
    }
}
