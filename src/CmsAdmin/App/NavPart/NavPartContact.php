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
 * Konfiguracja nawigatora kontaktu
 */
class NavPartContact extends \Mmi\Navigation\NavigationConfig
{

    /**
     * Zwraca menu
     * @return \Mmi\Navigation\NavigationConfigElement
     */
    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Kontakt')
                ->setIcon('fa-comments')
                ->setUri('#')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista zgłoszeń')
                    ->setIcon('fa-table')
                    ->setModule('cmsAdmin')
                    ->setController('contact'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Lista tematów')
                    ->setIcon('fa-table')
                    ->setModule('cmsAdmin')
                    ->setController('contact')
                    ->setAction('subject'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Dodaj temat')
                    ->setIcon('fa-plus')
                    ->setModule('cmsAdmin')
                    ->setController('contact')
                    ->setAction('editSubject'));
    }

}
