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
	 * Przypina wartość atrybutu do obiektu z id
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
	 * Ustawia relację z obiektu z id
	 * @param array $attributeValues tablica z id grup atrybutów
	 */
	public function createAttributeValueRelations(array $attributeValues) {
		//usuwanie relacji
		self::deleteAttributeValueRelations();
		//iteracja po grupach atrybutów
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
	public function getAttributeValueRelations() {
		return (new CmsAttributeValueRelationQuery)
				->join('cms_attribute_value')->on('cms_attribute_value_id')
				->whereObject()->equals($this->_object)
				->andFieldObjectId()->equals($this->_objectId)
				->findPairs('cms_attribute_value.id', 'cms_attribute_value.name');
	}

}
