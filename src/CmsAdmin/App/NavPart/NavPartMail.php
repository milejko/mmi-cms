<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

class NavPartMail extends \Mmi\Navigation\NavigationConfig
{

    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Poczta')
                ->setModule('cmsAdmin')
                ->setController('mail')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Wyślij z kolejki')
                    ->setModule('cmsAdmin')
                    ->setController('mail')
                    ->setAction('send'))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Szablony')
                    ->setModule('cmsAdmin')
                    ->setController('mailDefinition')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Dodaj')
                        ->setModule('cmsAdmin')
                        ->setController('mailDefinition')
                        ->setAction('edit')))
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Serwery')
                    ->setModule('cmsAdmin')
                    ->setController('mailServer')
                    ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                        ->setLabel('Dodaj')
                        ->setModule('cmsAdmin')
                        ->setController('mailServer')
                        ->setAction('edit')));
    }

}
