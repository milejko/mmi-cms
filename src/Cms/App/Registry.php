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
 * Klasa rejestru aplikacji CMS
 */
abstract class Registry extends \Mmi\App\Registry {

	/**
	 * Obiekt ACL
	 * @var \Mmi\Security\Acl
	 */
	public static $acl;

	/**
	 * Obiekt autoryzacji
	 * @var \Mmi\Security\Auth
	 */
	public static $auth;

	/**
	 * Obiekt navigacji
	 * @var \Mmi\Navigation\Navigation
	 */
	public static $navigation;

	/**
	 * Obiekt translacji
	 * @var \Mmi\Translate
	 */
	public static $translate;

}
