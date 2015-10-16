<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

/**
 * Klasa konfiguracji aplikacji CMS
 */
abstract class CmsKernelConfig extends \Mmi\App\KernelConfig {

	/**
	 * Konfiguracja autoryzacji CMS (LDAP)
	 * @var \Cms\App\LdapConfig
	 */
	public $ldap;

	/**
	 * Konfiguracja nawigatora
	 * @var \Mmi\Navigation\NavigationConfig
	 */
	public $navigation;

	public function __construct() {
		//ładowanie konfiguracji MMi
		parent::__construct();
		
		//konfiguracja LDAP
		$this->ldap = new \Mmi\Ldap\LdapConfig;
		
		//konfiguracja pluginów
		$this->plugins = ['\Cms\App\CmsFrontControllerPlugin'];

		//dodawanie rout CMS
		$this->router->setRoutes((new \Cms\App\CmsRouterConfig)->toArray());
		
		//nazwa sesji
		$this->session->name = 'mmi-cms';

		//konfiguracja nawigatora
		$this->navigation = new \Mmi\Navigation\NavigationConfig;
		//dodawanie nawigatora CMS
		$this->navigation->addElement(\CmsAdmin\App\CmsNavigationConfig::getMenu());

		//konfiguracja bazy danych
		$this->db = new \Mmi\Db\DbConfig;
		$this->db->driver = 'sqlite';
		$this->db->host = BASE_PATH . '/var/cms-db.sqlite';
	}

}
