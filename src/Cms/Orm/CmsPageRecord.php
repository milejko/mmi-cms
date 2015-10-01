<?php

namespace Cms\Orm;

class CmsPageRecord extends \Mmi\Orm\Record {

	public $id;
	public $name;
	public $cmsNavigationId;
	public $cmsRouteId;
	public $text;
	public $active;
	public $dateAdd;
	public $dateModify;
	public $cmsAuthId;

}
