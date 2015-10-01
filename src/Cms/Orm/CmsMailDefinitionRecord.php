<?php

namespace Cms\Orm;

/**
 * Rekord definicji (szablonu) maila
 */
class CmsMailDefinitionRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $cmsMailServerId;
	public $name;
	public $replyTo;
	public $fromName;
	public $subject;
	public $message;
	public $html;
	public $dateAdd;
	public $dateModify;
	public $active;

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
