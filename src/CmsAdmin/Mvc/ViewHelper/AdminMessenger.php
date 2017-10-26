<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

/**
 * Helper messengera
 */
class AdminMessenger extends \Mmi\Mvc\ViewHelper\Messenger
{

    //szablon menu
    CONST TEMPLATE = 'cmsAdmin/mvc/view-helper/admin-messenger';

    /**
     * Metoda renderingu
     * @return string
     */
    public function adminMessenger()
    {
        return self::messenger();
    }

}
