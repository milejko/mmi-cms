<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Config;

/**
 * Klasa konfiguracji routera
 */
class Ldap {

	/**
	 * Aktywny
	 * @var boolean
	 */
	public $active = false;
	
	/**
	 * Adres lub tablica adresów
	 * @var string|array
	 */
	public $address;
	
	/**
	 * Użytkownik
	 * @var string
	 */
	public $user;
	
	/**
	 * Hasło
	 * @var string
	 */
	public $password;
	
	/**
	 * Domena
	 * @var string
	 */
	public $domain;
	
	/**
	 * Wzorzec logowania (domyślnie %s)
	 * np. %s@example.com
	 * np. uid=%s,dc=example,dc=com
	 * @var string
	 */
	public $dnPattern = '%s';

}
