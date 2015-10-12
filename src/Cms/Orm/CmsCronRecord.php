<?php

namespace Cms\Orm;

/**
 * Rekord harmonogramu
 */
class CmsCronRecord extends \Mmi\Orm\Record {

	/**
	 * Identyfikator
	 * @var integer
	 */
	public $id;
	public $active;
	
	/**
	 * Minuta
	 * @var integer 
	 */
	public $minute;
	
	/**
	 * Godzina
	 * @var string 
	 */
	public $hour;
	
	/**
	 * Dzień miesiąca
	 * @var type 
	 */
	public $dayOfMonth;
	
	/**
	 * Miesiąc
	 * @var integer
	 */
	public $month;
	
	/**
	 * Dzień tygodnia
	 * @var integer
	 */
	public $dayOfWeek;
	public $name;
	public $description;
	public $module;
	public $controller;
	public $action;
	public $dateAdd;
	public $dateModified;
	
	/**
	 * Data ostatniego uruchomienia
	 * @var string
	 */
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
