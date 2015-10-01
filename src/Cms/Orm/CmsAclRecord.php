<?php

namespace Cms\Orm;

/**
 * Rekord uprawnień
 */
class CmsAclRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsRoleId;
	public $module;
	public $controller;
	public $action;
	public $access;

	/**
	 * Zapis rekordu uprawnień
	 * @return boolean
	 */
	public function save() {
		if ($this->getOption('object')) {
			$object = explode(':', $this->getOption('object'));
			$this->module = isset($object[0]) ? strtolower($object[0]) : null;
			$this->controller = isset($object[1]) ? strtolower($object[1]) : null;
			$this->action = isset($object[2]) ? strtolower($object[2]) : null;
		}
		return parent::save() && $this->_clearCache();
	}

	/**
	 * Usunięcie
	 * @return boolean
	 */
	public function delete() {
		return parent::delete() && $this->_clearCache();
	}

	/**
	 * Usunięcie cache
	 * @return boolean
	 */
	protected function _clearCache() {
		\App\Registry::$cache->remove('Mmi-Navigation-');
		\App\Registry::$cache->remove('Mmi-Navigation-' . \Mmi\App\FrontController::getInstance()->getRequest()->lang);
		\App\Registry::$cache->remove('Mmi-Acl');
		return true;
	}

}
