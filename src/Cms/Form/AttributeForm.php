<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

use \Cms\Model\AttributeValueRelationModel,
	\Cms\Model\AttributeRelationModel;

/**
 * Formularz CMS z atrybutami
 */
abstract class AttributeForm extends Form {

	/**
	 * Obiekt
	 * @var string
	 */
	private $_saveToObject;

	/**
	 * Obiekty formularza wygenerowane z atrybutów
	 * @var \Mmi\Form\Element\ElementAbstract[]
	 */
	private $_cmsAttributeElements = [];

	/**
	 * Atrybuty wynikające z relacji
	 * @var \Cms\Orm\CmsAttributeRecord[] 
	 */
	private $_cmsAttributes = [];

	/**
	 * Wartości atrybutów wynikających z relacji
	 * @var \Cms\Orm\CmsAttributeValueRecord[]
	 */
	private $_cmsAttributeValues = [];

	/**
	 * Inicjalizacja atrybutów
	 * @param string $object przypięte do obiektu
	 * @param string $objectId przypięte do id obiektu
	 * @param string $saveToObject zapis do obiektu
	 * @param string $label opcjonalna labelka atrybutów
	 * @return boolean zwraca false jeśli brak atrybutów
	 */
	public function initAttributes($object, $objectId, $saveToObject, $label = null) {
		//ustalenie obiektu do zapisu relacji
		$this->_saveToObject = $saveToObject;
		//pobranie przypisanych atrybutów
		$this->_cmsAttributes = (new AttributeRelationModel($object, $objectId))->getAttributes();
		//pobranie wartości atrybutów
		$this->_cmsAttributeValues = (new AttributeValueRelationModel($this->_saveToObject, $this->getRecord()->id))->getAttributeValues();
		//brak atrybutów
		if (!$this->_hasPrintableAttributes()) {
			return false;
		}
		//jeśli istnieje labelka i atrybuty
		if ($label) {
			//dodawanie label
			$this->addElementLabel('attributes-' . $object)->setLabel($label);
		}
		//iteracja po atrybutach
		foreach ($this->_cmsAttributes as $attribute) {
			//zmaterializowany, odziedziczony
			if ($attribute->isMaterializedInherited()) {
				continue;
			}
			//dodawanie skonfigurowanego pola
			$this->addElement($this->_createFieldByAttribute($attribute));
		}
		//dodano atrybuty
		return true;
	}

	/**
	 * Ustawia w rekordzie zmaterializowane atrybuty
	 */
	public function beforeSave() {
		//iteracja po elementach dodanych przez atrybuty
		foreach ($this->_cmsAttributeElements as $attributeId => $element) {
			//wyszukiwanie atrybutu
			$attribute = $this->_findAttributeById($attributeId);
			//jeśli atrybut jest zmaterializowany
			if (null !== $attribute && $attribute->isMaterialized()) {
				//ustawienie w wartości rekordzie
				$this->getRecord()->{$attribute->key} = $element->getValue();
			}
		}
	}

	/**
	 * Zapisuje atrybuty powiązane z formularzem
	 * @return bool
	 */
	public function afterSave() {
		//brak obiektu
		if (!$this->_saveToObject) {
			return true;
		}
		//czyszczenie relacji
		(new AttributeValueRelationModel($this->_saveToObject, $this->getRecord()->id))->deleteAttributeValueRelations();
		//iteracja po elementach
		foreach ($this->_cmsAttributeElements as $attributeId => $element) {
			//zapis relacji
			$this->_createValueRelationByElement($attributeId, $element);
		}
		//iteracja po atrybutach
		foreach ($this->_cmsAttributes as $attribute) {
			//nie jest zmaterializowany, odziedziczony
			if (!$attribute->isMaterializedInherited()) {
				continue;
			}
			//brak obiektu do dziedziczenia
			if (null === $element = $this->getElement($attribute->key)) {
				throw new \Exception('No inherited attribute: ' . $attribute->key);
			}
			//zapis relacji z odziedziczonego elementu formularza (występującego w oryginalnym formularzu)
			$this->_createValueRelationByElement($attribute->id, $element);
			
		}
		//zapis udany
		return true;
	}

	/**
	 * Znajduje atrybut
	 * @param integer $attributeId
	 * @return \Cms\Orm\CmsAttributeRecord
	 */
	private function _findAttributeById($attributeId) {
		//iteracja po atrybutach
		foreach ($this->_cmsAttributes as $attribute) {
			//atrybut znaleziony
			if ($attribute->id == $attributeId) {
				return $attribute;
			}
		}
	}

	/**
	 * Zapis relacji
	 * @param type $attributeId
	 * @param \Mmi\Form\Element\ElementAbstract $element
	 */
	private function _createValueRelationByElement($attributeId, \Mmi\Form\Element\ElementAbstract $element) {
		//element ignorowany
		if ($element->getIgnore()) {
			return;
		}
		//checkboxy
		if ($element instanceof \Mmi\Form\Element\Checkbox) {
			return (new AttributeValueRelationModel($this->_saveToObject, $this->getRecord()->id))->createAttributeValueRelationByValue($attributeId, (integer)$element->isChecked());
		}
		//zwykła, skalarna wartość
		if (!is_array($element->getValue())) {
			return (new AttributeValueRelationModel($this->_saveToObject, $this->getRecord()->id))->createAttributeValueRelationByValue($attributeId, $element->getValue());
		}
		//tablica
		foreach ($element->getValue() as $value) {
			(new AttributeValueRelationModel($this->_saveToObject, $this->getRecord()->id))->createAttributeValueRelationByValue($attributeId, $value);
		}
	}

	/**
	 * Tworzy pole na podstawie atrybutu
	 * @param \Cms\Orm\CmsAttributeRecord $attribute
	 * @return \Mmi\Form\Element\ElementAbstract
	 */
	private function _createFieldByAttribute(\Cms\Orm\CmsAttributeRecord $attribute) {
		//konfiguracja pola
		$field = (new $attribute->fieldClass('cmsAttribute-' . $attribute->id))
			->setLabel($attribute->name)
			->setDescription($attribute->description)
			->setValue($attribute->isRestricted() ? $this->_arrayValueByAttributeId($attribute->id) : $this->_scalarValueByAttributeId($attribute->id));
		//checkbox zaznaczony
		if ($field instanceof \Mmi\Form\Element\Checkbox && $this->_scalarValueByAttributeId($attribute->id)) {
			$field->setChecked();
		}
		//multiopcje
		if ($attribute->isRestricted()) {
			$options = (new \Cms\Orm\CmsAttributeValueQuery)->whereCmsAttributeId()->equals($attribute->id)->orderAscValue()->findPairs('value', 'value');
			$field->setMultioptions($attribute->isMultiple() ? $options : [null => '---'] + $options);
		}
		//pole wymagane
		if ($attribute->required) {
			$field->setRequired();
		}
		//czy pole jest wgrywarką plików
		if ($attribute->isUploader()) {
			//obiektem dla uploadera jest obiekt główny + klucz atrybutu, ignorowanie pola
			$field->setObject($this->_saveToObject . '-' . $attribute->key);
			$field->setValue($this->_saveToObject . '-' . $attribute->key);
		}
		//walidatory
		if ($attribute->validatorClasses) {
			//iteracja po walidatorach
			foreach (explode(',', $attribute->validatorClasses) as $validatorClass) {
				//dodawanie walidatora
				$field->addValidator(new $validatorClass);
			}
		}
		//filtry
		if ($attribute->filterClasses) {
			//iteracja po filtrach
			foreach (explode(',', $attribute->filterClasses) as $filterClass) {
				//dodawanie filtra
				$field->addFilter(new $filterClass);
			}
		}
		//unikalność
		if ($attribute->unique) {
			$field->addValidatorRecordUnique((new \Cms\Orm\CmsAttributeValueQuery)
					->join('cms_attribute_value_relation')->on('id', 'cms_attribute_value_id')
					->whereCmsAttributeId()->equals($attribute->id)
					->where('object', 'cms_attribute_value_relation')->equals($this->_saveToObject), 'value', $this->getRecord()->id
			);
		}
		//zwrot skonfigurowanego pola
		return $this->_cmsAttributeElements[$attribute->id] = $field;
	}

	/**
	 * Pobiera pojedynczą wartość z relacji
	 * @param integer $attributeId
	 * @return mixed
	 */
	private function _scalarValueByAttributeId($attributeId) {
		//iteracja po wartościach
		foreach ($this->_cmsAttributeValues as $valueRecord) {
			//znaleziony atrybut
			if ($valueRecord->cmsAttributeId == $attributeId) {
				//zwrot wartości
				return $valueRecord->value;
			}
		}
	}

	/**
	 * Pobiera wszystkie wartości z relacji
	 * @param integer $attributeId
	 * @return array
	 */
	private function _arrayValueByAttributeId($attributeId) {
		//tablica wartości
		$values = [];
		//iteracja po wartościach
		foreach ($this->_cmsAttributeValues as $valueRecord) {
			//znaleziony atrybut
			if ($valueRecord->cmsAttributeId == $attributeId) {
				//dodanie wartości
				$values[] = $valueRecord->value;
			}
		}
		//zwrot wartości
		return $values;
	}
	
	/**
	 * Sprawdza posiadanie renderowanych atrybutów
	 * @return boolean
	 */
	private function _hasPrintableAttributes() {
		//iteracja po atrybutach
		foreach ($this->_cmsAttributes as $attribute) {
			//nie jest atrybutem odziedziczonym - czyli będzie wypisany przy renderingu
			if (!$attribute->isMaterializedInherited()) {
				return true;
			}
		}
		return false;
	}

}