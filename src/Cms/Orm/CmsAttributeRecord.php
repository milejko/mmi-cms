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
	public $filterClasses;
	public $validatorClasses;
	public $indexWeight;
	public $required;
	public $unique;
	public $materialized;

	/**
	 * Czy zmaterializowany
	 * @return boolean
	 */
	public function isMaterialized() {
		return ($this->materialized == 1);
	}
	
	/**
	 * Czy zmaterializowany, odziedziczony
	 * @return boolean
	 */
	public function isMaterializedInherited() {
		return ($this->materialized == 2);
	}
	
}
