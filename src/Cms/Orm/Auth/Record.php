<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Auth;

/**
 * Rekord użytkownika CMS
 */
class Record extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $name;
	public $username;
	public $email;
	public $password;
	public $lastIp;
	public $lastLog;
	public $lastFailIp;
	public $lastFailLog;
	public $failLogCount;
	public $logged;
	public $active;

}
