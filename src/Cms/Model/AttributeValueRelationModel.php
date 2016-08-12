<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsAttributeValueQuery,
	Cms\Orm\CmsAttributeValueRelationQuery,
	Cms\Orm\CmsAttributeValueRelationRecord;

/**
 * Model kategorii
 */
class AttributeValueRelationModel {

	/**
	 * Obiekt
	 * @var string
	 */
	private $_object;

	/**
	 * Id obiektu
	 * @var integer
	 */
	private $_objectId;

	/**
	 * Konstruktor
	 * @param string $object obiekt
	 * @param integer $objectId nieobowiązkowe id
	 */
	public function __construct($object, $objectId = null) {
		//przypisania
		$this->_object = $object;
		$this->_objectId = $objectId;
	}

	/**
	 * Przypina id atrybutu do obiektu z id
	 * @param integer $attributeValueId id kategorii
	 */
	public function createAttributeValueRelation($attributeValueId) {
		//niepoprawna kategoria
		if (null === $attributeValueRecord = (new CmsAttributeValueQuery)
			->findPk($attributeValueId)) {
			return;
		}
		//wyszukiwanie relacji
		$relationRecord = (new CmsAttributeValueRelationQuery)
			->whereCmsAttributeValueId()->equals($attributeValueRecord->id)
			->andFieldObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->findFirst();
		//znaleziona relacja - nic do zrobienia
		if (null !== $relationRecord) {
			return;
		}
		//tworzenie relacji
		$newRelationRecord = new CmsAttributeValueRelationRecord;
		$newRelationRecord->cmsAttributeValueId = $attributeValueRecord->id;
		$newRelationRecord->object = $this->_object;
		$newRelationRecord->objectId = $this->_objectId;
		//zapis
		$newRelationRecord->save();
	}

	/**
	 * Przypina wartość atrybutu do obiektu z id
	 * @param mixed $attributeValue
	 */
	public function createAttributeValueRelationByValue($attributeId, $attributeValue) {
		//wyszukiwanie rekordu
		if (null === ($valueRecord = (new \Cms\Orm\CmsAttributeValueQuery)
			->whereCmsAttributeId()->equals($attributeId)
			->andFieldValue()->equals($attributeValue)
			->findFirst())
		) {
			//tworzenie rekordu
			$valueRecord = new \Cms\Orm\CmsAttributeValueRecord;
			$valueRecord->cmsAttributeId = $attributeId;
			$valueRecord->value = $attributeValue;
			$valueRecord->save();
		}
		//błąd wartości
		if (!$valueRecord->id) {
			return;
		}
		//zapis relacji
		return $this->createAttributeValueRelation($valueRecord->id);
	}

	/**
	 * Ustawia relację z obiektu z id
	 * @param array $attributeValues tablica z id wartości atrybutów
	 */
	public function createAttributeValueRelations(array $attributeValues) {
		//usuwanie relacji
		self::deleteAttributeValueRelations();
		//iteracja po wartościach atrybutów
		foreach ($attributeValues as $attributeValueId) {
			//tworzenie relacji
			self::createAttributeValueRelation($attributeValueId, $this->_object, $this->_objectId);
		}
	}

	/**
	 * Usuwa kategorię z obiektu i id
	 * @param integer $attributeValueId id kategorii
	 */
	public function deleteAttributeValueRelation($attributeValueId) {
		//brak kategorii - nic do zrobienia
		if (null === $attributeValueRecord = (new CmsAttributeValueQuery)
			->findPk($attributeValueId)) {
			return;
		}
		//wyszukiwanie relacji
		if (null === $relationRecord = (new CmsAttributeValueRelationQuery)
			->whereCmsAttributeValueId()->equals($attributeValueRecord->id)
			->andFieldObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->findFirst()) {
			//brak relacji - nic do zrobienia
			return;
		}
		//usunięcie relacji
		$relationRecord->delete();
	}
	
	/**
	 * Usunięcie relacji z obiektu i id dla podanego id atrybutu
	 */
	public function deleteAttributeValueRelationsByAttributeId($attributeId) {
		//czyszczenie relacji z całego atrybutu
		(new CmsAttributeValueRelationQuery)
			->join('cms_attribute_value')->on('cms_attribute_value_id')
			->whereObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->andField('cms_attribute_id', 'cms_attribute_value')->equals($attributeId)
			->find()
			->delete();
	}

	/**
	 * Usunięcie wszystkich relacji z obiektu i id
	 */
	public function deleteAttributeValueRelations() {
		//czyszczenie relacji
		(new CmsAttributeValueRelationQuery)
			->whereObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->find()
			->delete();
	}

	/**
	 * Pobiera relacje dla obiektu z id
	 * @return array
	 */
	public function getAttributeValueIds() {
		return array_keys((new CmsAttributeValueRelationQuery)
				->join('cms_attribute_value')->on('cms_attribute_value_id')
				->whereObject()->equals($this->_object)
				->andFieldObjectId()->equals($this->_objectId)
				->findPairs('cms_attribute_value.id', 'cms_attribute_value.id'));
	}

	/**
	 * Pobiera rekordy wartości
	 * @return \Cms\Orm\CmsAttributeValueRecord[]
	 */
	public function getAttributeValues() {
		return (new CmsAttributeValueQuery)
				->join('cms_attribute')->on('cms_attribute_id')
				->join('cms_attribute_value_relation')->on('id', 'cms_attribute_value_id')
				->where('object', 'cms_attribute_value_relation')->equals($this->_object)
				->where('objectId', 'cms_attribute_value_relation')->equals($this->_objectId)
				->find();
	}
	
}
