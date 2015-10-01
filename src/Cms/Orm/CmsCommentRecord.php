<?php

namespace Cms\Orm;

class CmsCommentRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsAuthId;
	public $parentId;
	public $dateAdd;
	public $title;
	public $text;
	public $signature;
	public $ip;
	public $stars;
	public $object;
	public $objectId;

}
