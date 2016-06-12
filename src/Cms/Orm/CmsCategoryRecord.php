<?php

namespace Cms\Orm;

class CmsCategoryRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $name;
	public $description;
	public $uri;
	public $code;
	public $parentId;
	public $order;
	public $dateAdd;
	public $dateModify;
	public $active;

}
