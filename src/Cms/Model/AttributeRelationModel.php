<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsAttributeQuery,
	Cms\Orm\CmsAttributeRelationQuery,
	Cms\Orm\CmsAttributeRelationRecord;

/**
 * Model kategorii
 */
class AttributeRelationModel {

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
	 * Przypina kategorię do obiektu z id
	 * @param integer $attributeId id kategorii
	 */
	public function createAttributeRelation($attributeId) {
		//niepoprawna kategoria
		if (null === $attributeRecord = (new CmsAttributeQuery)
			->findPk($attributeId)) {
			return;
		}
		//wyszukiwanie relacji
		$relationRecord = (new CmsAttributeRelationQuery)
			->whereCmsAttributeId()->equals($attributeRecord->id)
			->andFieldObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->findFirst();
		//znaleziona relacja - nic do zrobienia
		if (null !== $relationRecord) {
			return;
		}
		//tworzenie relacji
		$newRelationRecord = new CmsAttributeRelationRecord;
		$newRelationRecord->cmsAttributeId = $attributeRecord->id;
		$newRelationRecord->object = $this->_object;
		$newRelationRecord->objectId = $this->_objectId;
		//zapis
		$newRelationRecord->save();
	}

	/**
	 * Ustawia relację z obiektu z id
	 * @param array $attributes tablica z id grup atrybutów
	 */
	public function createAttributeRelations(array $attributes) {
		//usuwanie relacji
		self::deleteAttributeRelations();
		//iteracja po grupach atrybutów
		foreach ($attributes as $attributeId) {
			//tworzenie relacji
			self::createAttributeRelation($attributeId, $this->_object, $this->_objectId);
		}
	}

	/**
	 * Usuwa kategorię z obiektu i id
	 * @param integer $attributeId id kategorii
	 */
	public function deleteAttributeRelation($attributeId) {
		//brak kategorii - nic do zrobienia
		if (null === $attributeRecord = (new CmsAttributeQuery)
			->findPk($attributeId)) {
			return;
		}
		//wyszukiwanie relacji
		if (null === $relationRecord = (new CmsAttributeRelationQuery)
			->whereCmsAttributeId()->equals($attributeRecord->id)
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
	 * Usunięcie wszystkich relacji z obiektu i id
	 */
	public function deleteAttributeRelations() {
		//czyszczenie relacji
		(new CmsAttributeRelationQuery)
			->whereObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->find()
			->delete();
	}

	/**
	 * Pobiera relacje dla obiektu z id
	 * @return array
	 */
	public function getAttributeIds() {
		return array_keys((new CmsAttributeRelationQuery)
				->join('cms_attribute')->on('cms_attribute_id')
				->whereObject()->equals($this->_object)
				->andFieldObjectId()->equals($this->_objectId)
				->findPairs('cms_attribute.id', 'cms_attribute.id'));
	}
	
	/**
	 * Pobiera atrybuty wynikające z relacji
	 * @return \Cms\Orm\CmsAttributeRecord[]
	 */
	public function getAttributes() {
		return (new CmsAttributeQuery)
				->join('cms_attribute_relation')->on('id', 'cms_attribute_id')
				->joinLeft('cms_attribute_value', 'cms_attribute_relation')->on('cms_attribute_value_id')
				->where('object', 'cms_attribute_relation')->equals($this->_object)
				->where('objectId', 'cms_attribute_relation')->equals($this->_objectId)
				->orderAsc('order', 'cms_attribute_relation')
				->find();
	}

}
