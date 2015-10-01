<?php

namespace Cms\Orm;

class CmsMailRecord extends \Mmi\Orm\Record {

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

}
