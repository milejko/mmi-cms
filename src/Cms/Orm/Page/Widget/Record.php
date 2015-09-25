<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Page\Widget;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $name;
	public $module;
	public $controller;
	public $action;
	public $params;
	public $active;

	public function save() {
		if ($this->getOption('widget')) {
			$widget = explode(':', $this->getOption('widget'));
			$this->module = strtolower($widget[0]);
			$this->controller = strtolower($widget[1]);
			$this->action = $widget[2];
		}

		return parent::save();
	}

	public function isExistWidgetEdit($action) {
		$structure = \Mmi\Structure::getStructure('module');
		return array_key_exists($action . 'Edit', $structure['cmsAdmin']['widget']);
	}

}
