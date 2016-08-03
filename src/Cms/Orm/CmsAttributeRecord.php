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
	
	public function save() {
		//klucz
		$this->key = (new \Mmi\Filter\Url)->filter($this->name);
		//zapis
		return parent::save();
	}
	
	/**
	 * Czy atrybut jest ograniczony do listy
	 * @return boolean
	 */
	public function isRestricted() {
		return $this->fieldClass == '\Mmi\Form\Element\Select' || $this->fieldClass == '\Mmi\Form\Element\MultiCheckbox';
	}
	
	/**
	 * Czy atrybut jest wielokrotny
	 * @return boolean
	 */
	public function isMultiple() {
		return $this->fieldClass == '\Mmi\Form\Element\MultiCheckbox';
	}

}
