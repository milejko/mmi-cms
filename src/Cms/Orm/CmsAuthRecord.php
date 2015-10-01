<?php

namespace Cms\Orm;

/**
 * Rekord użytkownika CMS
 */
class CmsAuthRecord extends \Mmi\Orm\Record {

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
