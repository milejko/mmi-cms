<?php

namespace Cms\Orm;

class CmsCronRecord extends \Mmi\Orm\Record {

	public $id;
	public $active;
	public $minute;
	public $hour;
	public $dayOfMonth;
	public $month;
	public $dayOfWeek;
	public $name;
	public $description;
	public $module;
	public $controller;
	public $action;
	public $dateAdd;
	public $dateModified;
	public $dateLastExecute;

}
