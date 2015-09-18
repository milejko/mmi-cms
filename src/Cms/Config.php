<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

abstract class Config extends \Mmi\Config {

	/**
	 * Konfiguracja autoryzacji CMS (LDAP)
	 * @var \Cms\Config\Ldap
	 */
	public $ldap;

	public function __construct() {

		parent::__construct();
		$this->ldap = new \Cms\Config\Ldap();
	}

}
