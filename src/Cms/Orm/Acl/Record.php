<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Acl;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $cmsRoleId;
	public $module;
	public $controller;
	public $action;
	public $access;

	public function save() {
		if ($this->getOption('object')) {
			$object = explode(':', $this->getOption('object'));
			$this->module = isset($object[0]) ? strtolower($object[0]) : null;
			$this->controller = isset($object[1]) ? strtolower($object[1]) : null;
			$this->action = isset($object[2]) ? strtolower($object[2]) : null;
		}
		$this->_clearCache();
		return parent::save();
	}

	public function delete() {
		$this->_clearCache();
		return parent::delete();
	}

	protected function _clearCache() {
		\App\Registry::$cache->remove('Mmi-Navigation-');
		\App\Registry::$cache->remove('Mmi-Navigation-' . \Mmi\Controller\Front::getInstance()->getRequest()->lang);
		\App\Registry::$cache->remove('Mmi-Acl');
	}

}
