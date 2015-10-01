<?php

namespace Cms\Orm;

class CmsMailServerRecord extends \Mmi\Orm\Record {

	public $id;
	public $address;
	public $port;
	public $username;
	public $password;
	public $from;
	public $dateAdd;
	public $dateModify;
	public $active;
	public $ssl;

}
