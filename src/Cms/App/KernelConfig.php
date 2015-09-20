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
abstract class KernelConfig extends \Mmi\App\KernelConfig {

	/**
	 * Konfiguracja autoryzacji CMS (LDAP)
	 * @var \Cms\App\LdapConfig
	 */
	public $ldap;

	/**
	 * Konfiguracja nawigatora
	 * @var \Mmi\Navigation\Config
	 */
	public $navigation;

	public function __construct() {
		//ładowanie konfiguracji MMi
		parent::__construct();
		
		//konfiguracja LDAP
		$this->ldap = new \Cms\App\LdapConfig();
		
		//konfiguracja pluginów
		$this->plugins = ['\Cms\App\FrontControllerPlugin'];

		//dodawanie rout CMS
		$this->router->setRoutes((new \Cms\App\RouterConfig())->toArray());
		
		//nazwa sesji
		$this->session->name = 'mmi-cms';

		//konfiguracja nawigatora
		$this->navigation = new \Mmi\Navigation\Config();
		//dodawanie nawigatora CMS
		$this->navigation->addElement(\CmsAdmin\App\Config\Navigation::getMenu());

		//konfiguracja bazy danych
		$this->db = new \Mmi\Db\Config();
		$this->db->driver = 'sqlite';
		$this->db->host = BASE_PATH . '/var/cms-db.sqlite';
	}

}
