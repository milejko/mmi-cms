<?php

namespace Cms\Orm;

class CmsAttributeRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $name;
	public $key;
	public $description;
	public $fieldClass;
	public $filterClasses;
	public $validatorClasses;
	public $indexWeight;
	public $required;
	public $unique;
	public $materialized;

}
