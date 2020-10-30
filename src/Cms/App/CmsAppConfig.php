<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

use Cms\App\CmsSkinsetConfig;
use Mmi\Navigation\NavigationConfig;
use Mmi\Ldap\LdapConfig;

/**
 * Klasa konfiguracji aplikacji CMS
 */
abstract class CmsAppConfig extends \Mmi\App\AppConfig
{

    /**
     * Konfiguracja autoryzacji CMS (LDAP)
     * @var LdapConfig
     */
    public $ldap;

    /**
     * Konfiguracja nawigatora
     * @var NavigationConfig
     */
    public $navigation;

    /**
     * Jakość miniatur jpg 1-100
     * @var integer
     */
    public $thumbQuality = 90;

    /**
     * Zestaw skór CMS CMSowe
     * @var CmsSkinsetConfig
     */
    public $skinset;

}
