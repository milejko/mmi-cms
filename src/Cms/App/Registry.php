<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

abstract class Registry extends \Mmi\App\Registry {

	/**
	 * Obiekt ACL
	 * @var \Mmi\Acl
	 */
	public static $acl;

	/**
	 * Obiekt autoryzacji
	 * @var \Mmi\Auth
	 */
	public static $auth;

	/**
	 * Konfiguracja
	 * @var \App\Config\Local
	 */
	public static $config;

	/**
	 * Obiekt adaptera bazodanowego
	 * @var \Mmi\Db\Adapter\Pdo\PdoAbstract
	 */
	public static $db;

	/**
	 * Obiekt navigacji
	 * @var \Mmi\Navigation
	 */
	public static $navigation;

	/**
	 * Obiekt translacji
	 * @var \Mmi\Translate
	 */
	public static $translate;

}
