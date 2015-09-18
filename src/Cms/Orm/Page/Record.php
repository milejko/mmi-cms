<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Page;

/**
 * Rekord strony CMS
 */
class Record extends \Mmi\Orm\Record {

	public $id;
	public $name;
	public $cmsNavigationId;
	public $cmsRouteId;
	public $text;
	public $active;
	public $dateAdd;
	public $dateModify;
	public $cmsAuthId;

	/**
	 * Usuwanie rekordu
	 * @return boolean
	 */
	public function delete() {
		//uwsuwanie standardowego rekordu
		if (!parent::delete()) {
			return false;
		}
		//usuwa powiązany element nawigacyjny
		$navigationRecord = \Cms\Orm\Navigation\Query::factory()->findPk($this->cmsNavigationId);
		$navigationRecord !== null && $navigationRecord->delete();
		//usuwa powiązaną routę
		$routeRecord = \Cms\Orm\Route\Query::factory()->findPk($this->cmsRouteId);
		$routeRecord !== null && $routeRecord->delete();
		return true;
	}

	/**
	 * Ustawia datę dodania
	 * @return boolean
	 */
	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

	/**
	 * Ustawia datę modyfikacji
	 * @return boolean
	 */
	protected function _update() {
		$this->dateModify = date('Y-m-d H:i:s');
		return parent::_update();
	}

}
