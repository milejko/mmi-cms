<?php

namespace Cms\Orm;

/**
 * Rekord atrybutu
 */
class CmsAttributeRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsAttributeTypeId;
	public $lang;
	public $name;
	public $key;
	public $description;
	public $fieldOptions;
	public $indexWeight;
	
}
