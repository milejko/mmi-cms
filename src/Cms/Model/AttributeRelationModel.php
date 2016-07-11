<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsAttributeGroupQuery,
	Cms\Orm\CmsAttributeGroupRelationQuery,
	Cms\Orm\CmsAttributeGroupRelationRecord;

/**
 * Model kategorii
 */
class AttributeGroupRelationModel {

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
	 * @param integer $attributeGroupId id kategorii
	 */
	public function createAttributeGroupRelation($attributeGroupId) {
		//niepoprawna kategoria
		if (null === $attributeGroupRecord = (new CmsAttributeGroupQuery)
			->findPk($attributeGroupId)) {
			return;
		}
		//wyszukiwanie relacji
		$relationRecord = (new CmsAttributeGroupRelationQuery)
			->whereCmsAttributeGroupId()->equals($attributeGroupRecord->id)
			->andFieldObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->findFirst();
		//znaleziona relacja - nic do zrobienia
		if (null !== $relationRecord) {
			return;
		}
		//tworzenie relacji
		$newRelationRecord = new CmsAttributeGroupRelationRecord;
		$newRelationRecord->cmsAttributeGroupId = $attributeGroupRecord->id;
		$newRelationRecord->object = $this->_object;
		$newRelationRecord->objectId = $this->_objectId;
		//zapis
		$newRelationRecord->save();
	}

	/**
	 * Ustawia relację z obiektu z id
	 * @param array $categories tablica z id kategorii
	 */
	public function createAttributeGroupRelations(array $categories) {
		//usuwanie relacji
		self::deleteAttributeGroupRelations();
		//iteracja po kategoriach
		foreach ($categories as $attributeGroupId) {
			//tworzenie relacji
			self::createAttributeGroupRelation($attributeGroupId, $this->_object, $this->_objectId);
		}
	}

	/**
	 * Usuwa kategorię z obiektu i id
	 * @param integer $attributeGroupId id kategorii
	 */
	public function deleteAttributeGroupRelation($attributeGroupId) {
		//brak kategorii - nic do zrobienia
		if (null === $attributeGroupRecord = (new CmsAttributeGroupQuery)
			->findPk($attributeGroupId)) {
			return;
		}
		//wyszukiwanie relacji
		if (null === $relationRecord = (new CmsAttributeGroupRelationQuery)
			->whereCmsAttributeGroupId()->equals($attributeGroupRecord->id)
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
	public function deleteAttributeGroupRelations() {
		//czyszczenie relacji
		(new CmsAttributeGroupRelationQuery)
			->whereObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->find()
			->delete();
	}

	/**
	 * Pobiera relacje dla obiektu z id
	 * @return array
	 */
	public function getAttributeGroupRelations() {
		return (new CmsAttributeGroupRelationQuery)
				->join('cms_attributeGroup')->on('cms_attributeGroup_id')
				->whereObject()->equals($this->_object)
				->andFieldObjectId()->equals($this->_objectId)
				->findPairs('cms_attributeGroup.id', 'cms_attributeGroup.name');
	}

}
