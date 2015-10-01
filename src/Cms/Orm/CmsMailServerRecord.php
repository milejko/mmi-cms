<?php

namespace Cms\Orm;

/**
 * Rekord serwera mailowego
 */
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

	/**
	 * Aktualizacja rekordu
	 * @return boolean
	 */
	protected function _update() {
		$this->dateModify = date('Y-m-d H:i:s');
		return parent::_update();
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
