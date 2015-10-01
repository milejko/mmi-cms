<?php

namespace Cms\Orm;

/**
 * Rekord harmonogramu
 */
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

	/**
	 * Zapis rekordu
	 * @return boolean
	 */
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

	/**
	 * Wstawienie rekordu
	 * @return boolean
	 */
	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

}
