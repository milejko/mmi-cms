<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Stat\Label;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $object;
	public $label;
	public $description;

	protected function _insert() {
		$this->lang = \Mmi\Controller\Front::getInstance()->getRequest()->lang;
		return parent::_insert();
	}

}
