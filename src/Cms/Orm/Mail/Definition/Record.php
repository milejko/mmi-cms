<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Mail\Definition;

class Record extends \Mmi\Orm\Record {

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

	protected function _update() {
		$this->dateModify = date('Y-m-d H:i:s');
		return parent::_update();
	}

	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

}
