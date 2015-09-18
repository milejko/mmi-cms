<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Mail;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $cmsMailDefinitionId;
	public $fromName;
	public $to;
	public $replyTo;
	public $subject;
	public $message;
	public $attachements;
	public $type;
	public $dateAdd;
	public $dateSent;
	public $dateSendAfter;
	public $active;

	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

}
