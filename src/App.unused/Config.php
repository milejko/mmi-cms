<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2014 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace App;

/**
 * Ogólna konfiguracja aplikacji
 */
class Config extends \Cms\App\CmsKernelConfig
{

	/**
	 * Inicjalizacja konfiguracji
	 */
	public function __construct()
	{

		//konfiguracja lokalnego bufora
		$this->localCache = new \Mmi\Cache\CacheConfig;
		$this->localCache->handler = 'file';
		$this->localCache->path = BASE_PATH . '/var/cache';

		//konfiguracja bufora
		$this->cache = new \Mmi\Cache\CacheConfig;
		$this->cache->handler = 'file';
		$this->cache->path = BASE_PATH . '/var/cache';

		//ustawienia loggera
		$this->log = new \Mmi\Log\LogConfig;

		//konfiguracja LDAP
		$this->ldap = new \Mmi\Ldap\LdapConfig;

		//konfiguracja pluginów
		$this->plugins = ['\Cms\App\CmsFrontControllerPlugin'];

		//ustawienia routera
		$this->router = new \Mmi\Mvc\RouterConfig;
		$this->router->setRoutes((new \Cms\App\CmsRouterConfig)->getRoutes());

		//konfiguracja sesji
		$this->session = new \Mmi\Session\SessionConfig;
		$this->session->handler = 'files';
		$this->session->path = BASE_PATH . '/var/session';
		$this->session->name = 'mmi-cms';

		//konfiguracja nawigatora
		$this->navigation = new \Mmi\Navigation\NavigationConfig;
		$this->navigation->addElement(\CmsAdmin\App\CmsNavigationConfig::getMenu());

		//języki
		$this->languages = ['en'];

		//konfiguracja bazy danych
		$this->db = new \Mmi\Db\DbConfig;
		$this->db->driver = 'mysql';
		$this->db->user = 'mmi-cms';
		$this->db->password = 'mmi-cms';
		$this->db->name = 'mmi-cms';
	}
}
