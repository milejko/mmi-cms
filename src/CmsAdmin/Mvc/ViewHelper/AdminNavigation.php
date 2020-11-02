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
 * Helper nawigatora
 */
class AdminNavigation extends \Mmi\Mvc\ViewHelper\Navigation
{

    /**
     * Separator breadcrumbs
     * @var string
     */
    protected $_separator = '';
    //szablon menu
    const TEMPLATE = 'cmsAdmin/mvc/view-helper/adminNavigation/menu-item';

    /**
     * Metoda główna, zwraca swoją instancję
     * @return \Mmi\Mvc\ViewHelper\Navigation
     */
    public function adminNavigation()
    {
        return parent::navigation();
    }
}
