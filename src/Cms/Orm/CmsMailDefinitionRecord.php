<?php

namespace Cms\Orm;

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

}
