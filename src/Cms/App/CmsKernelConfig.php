<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

/**
 * Klasa konfiguracji aplikacji CMS
 */
abstract class CmsKernelConfig extends \Mmi\App\KernelConfig
{

    /**
     * Konfiguracja autoryzacji CMS (LDAP)
     * @var \Mmi\Ldap\LdapConfig
     */
    public $ldap;

    /**
     * Konfiguracja nawigatora
     * @var \Mmi\Navigation\NavigationConfig
     */
    public $navigation;

    /**
     * Jakość miniatur jpg 1-100
     * @var integer
     */
    public $thumbQuality = 90;

    /**
     * Konfiguracja obsługi kategorii
     * @var \Cms\Config\CategoryConfig
     */
    public $category;

}
