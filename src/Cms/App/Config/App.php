<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
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
	}

}
