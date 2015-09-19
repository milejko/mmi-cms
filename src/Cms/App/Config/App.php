<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App\Config;

/**
 * Klasa konfiguracji aplikacji CMS
 */
abstract class App extends \Mmi\App\Config\App {

	/**
	 * Konfiguracja autoryzacji CMS (LDAP)
	 * @var \Cms\App\Config\Ldap
	 */
	public $ldap;

	public function __construct() {

		//ładowanie konfiguracji rodzica
		parent::__construct();

		$this->ldap = new \Cms\App\Config\Ldap();

		$this->plugins = ['\Cms\Controller\Plugin'];

		//moduł + kontroler index + akcja index np. /news
		$cmsRoutes = new \Cms\App\Config\Router();

		//dodawanie rout CMS
		$this->router->setRoutes($cmsRoutes->toArray());
		
		//konfiguracja nawigatora
		$this->navigation->addElement(\CmsAdmin\App\Config\Navigation::getMenu());

		$this->db->driver = 'sqlite';
		$this->db->host = BASE_PATH . '/var/cms-db.sqlite';
	}

}
