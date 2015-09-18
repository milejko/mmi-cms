<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Cron;

class Record extends \Mmi\Orm\Record {

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

	public function save() {
		if ($this->getOption('object')) {
			$params = explode('_', $this->getOption('object'));
			if (count($params) == 3) {
				$this->module = $params[0];
				$this->controller = $params[1];
				$this->action = $params[2];
			} else {
				$this->module = null;
				$this->controller = null;
				$this->action = null;
			}
		}
		$this->dateModified = date('Y-m-d H:i:s');
		return parent::save();
	}

	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

}
