<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

class NavPartConnector extends \Mmi\Navigation\NavigationConfig
{

    public static function getMenu()
    {
        return (new \Mmi\Navigation\NavigationConfigElement)
                ->setLabel('Import danych')
                ->setModule('cmsAdmin')
                ->setController('connector')
                ->addChild((new \Mmi\Navigation\NavigationConfigElement)
                    ->setLabel('Import plików')
                    ->setModule('cmsAdmin')
                    ->setController('connector')
                    ->setAction('files')
                    ->setDisabled()
        );
    }

}
