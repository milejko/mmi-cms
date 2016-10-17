<?php

namespace Cms\Orm;

/**
 * Rekord atrybutu
 */
class CmsAttributeRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $name;
	public $key;
	public $description;
	public $fieldClass;
	public $fieldOptions;
	public $filterClasses;
	public $validatorClasses;
	public $indexWeight;
	public $required;
	public $unique;
	public $materialized;

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

	/**
	 * Czy pole wgrywania pliku
	 * @return boolean
	 */
	public function isUploader() {
		return $this->fieldClass == '\Mmi\Form\Element\File' || $this->fieldClass == '\Cms\Form\Element\Plupload';
	}
	
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
	
	/**
	 * Pobiera dostępne klasy pól
	 * @return array
	 */
	public function getFieldClasses() {
		return [
			'\Mmi\Form\Element\Checkbox' => 'checkbox',
			'\Cms\Form\Element\DatePicker' => 'data',
			'\Cms\Form\Element\DateTimePicker' => 'data i czas',
			'\Cms\Form\Element\TinyMce' => 'edytor',
			'\Mmi\Form\Element\Text' => 'tekst',
			'\Mmi\Form\Element\Textarea' => 'tekst wielolinijkowy',
			'\Mmi\Form\Element\File' => 'uploader jednego pliku',
			'\Cms\Form\Element\Plupload' => 'uploader wielu plików',
			'\Mmi\Form\Element\Select' => 'wybór jednokrotny',
			'\Mmi\Form\Element\MultiCheckbox' => 'wybór wielokrotny',
		];
	}

}
