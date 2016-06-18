<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCategoryQuery,
	Cms\Orm\CmsCategoryRelationQuery,
	Cms\Orm\CmsCategoryRelationRecord;

/**
 * Model kategorii
 */
class CategoryModel {

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
	 * @param integer $categoryId id kategorii
	 */
	public function createCategoryRelation($categoryId) {
		//niepoprawna kategoria
		if (null === $categoryRecord = (new CmsCategoryQuery)
			->findPk($categoryId)) {
			return;
		}
		//wyszukiwanie relacji
		$relationRecord = (new CmsCategoryRelationQuery)
			->whereCmsCategoryId()->equals($categoryRecord->id)
			->andFieldObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->findFirst();
		//znaleziona relacja - nic do zrobienia
		if (null !== $relationRecord) {
			return;
		}
		//tworzenie relacji
		$newRelationRecord = new CmsCategoryRelationRecord;
		$newRelationRecord->cmsCategoryId = $categoryRecord->id;
		$newRelationRecord->object = $this->_object;
		$newRelationRecord->objectId = $this->_objectId;
		//zapis
		$newRelationRecord->save();
	}

	/**
	 * Ustawia relację z obiektu z id
	 * @param array $categories tablica z id kategorii
	 */
	public function createCategoryRelations(array $categories) {
		//usuwanie relacji
		self::deleteCategoryRelations();
		//iteracja po kategoriach
		foreach ($categories as $categoryId) {
			//tworzenie relacji
			self::createCategoryRelation($categoryId, $this->_object, $this->_objectId);
		}
	}

	/**
	 * Usuwa kategorię z obiektu i id
	 * @param integer $categoryId id kategorii
	 */
	public function deleteCategoryRelation($categoryId) {
		//brak kategorii - nic do zrobienia
		if (null === $categoryRecord = (new CmsCategoryQuery)
			->findPk($categoryId)) {
			return;
		}
		//wyszukiwanie relacji
		if (null === $relationRecord = (new CmsCategoryRelationQuery)
			->whereCmsCategoryId()->equals($categoryRecord->id)
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
	public function deleteCategoryRelations() {
		//czyszczenie relacji
		(new CmsCategoryRelationQuery)
			->whereObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->find()
			->delete();
	}

	/**
	 * Pobiera relacje dla obiektu z id
	 * @return array
	 */
	public function getCategoryRelations() {
		return (new CmsCategoryRelationQuery)
				->join('cms_category')->on('cms_category_id')
				->whereObject()->equals($this->_object)
				->andFieldObjectId()->equals($this->_objectId)
				->findPairs('cms_category.id', 'cms_category.name');
	}

}
