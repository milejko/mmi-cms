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
	Cms\Orm\CmsCategoryRecord,
	Cms\Orm\CmsCategoryRelationQuery,
	Cms\Orm\CmsCategoryRelationRecord;

class CategoryModel {

	/**
	 * Przypina kategorię do obiektu z id
	 * @param integer $categoryId id kategorii
	 * @param string $object obiekt
	 * @param int $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function addCategory($categoryId, $object, $objectId = null) {
		//kreacja categoryu jeśli brak
		if (null === $categoryRecord = (new CmsCategoryQuery)
				->whereTag()->equals($filteredTag)
				->findFirst()) {
			$categoryRecord = new CmsCategoryRecord;
			$categoryRecord->category = $filteredTag;
			$categoryRecord->save();
		}
		//wyszukiwanie relacji
		$relationRecord = (new CmsCategoryRelationQuery)
			->whereCmsCategoryId()->equals($categoryRecord->id)
			->andFieldObject()->equals($object)
			->andFieldObjectId()->equals($objectId)
			->findFirst();
		//znaleziona relacja - nic do zrobienia
		if (null !== $relationRecord) {
			return true;
		}
		//tworzenie relacji
		$newRelationRecord = new CmsCategoryRelationRecord;
		$newRelationRecord->cmsTagId = $categoryRecord->id;
		$newRelationRecord->object = $object;
		$newRelationRecord->objectId = $objectId;
		//zapis
		return $newRelationRecord->save();
	}

	/**
	 * Usuwa kategorię z obiektu i id
	 * @param integer $categoryId id kategorii
	 * @param string $object obiekt
	 * @param int $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function removeCategory($categoryId, $object, $objectId = null) {
		//brak categoryu - nic do zrobienia
		if (null === $categoryRecord = (new CmsCategoryQuery)
				->findPk($categoryId)) {
			return false;
		}
		//wyszukiwanie relacji
		if (null === $relationRecord = (new CmsCategoryRelationQuery)
			->whereCmsCategoryId()->equals($categoryRecord->id)
			->andFieldObject()->equals($object)
			->andFieldObjectId()->equals($objectId)
			->findFirst()) {
			//brak relacji - nic do zrobienia
			return false;
		}
		//usunięcie relacji
		return $relationRecord->delete();
	}

	/**
	 * Ustawia kategorie
	 * @param array $categories tablica z id kategorii
	 * @param string $object
	 * @param int $objectId
	 * @return boolean
	 */
	public static function setCategories(array $categories, $object, $objectId = null) {
		//czyszczenie relacji
		(new CmsCategoryRelationQuery)
			->whereObject()->equals($object)
			->andFieldObjectId()->equals($objectId)
			->find()
			->delete();
		//dodawanie categoryów
		foreach ($categories as $categoryId) {
			self::addCategory($categoryId, $object, $objectId);
		}
		return true;
	}
	
	public static function getCategories($object, $objectId) {
		return (new CmsCategoryRelationQuery)
			->join('cms_category')->on('cms_category_id')
			->whereObject()->equals($object)
			->andFieldObjectId()->equals($objectId)
			->findPairs('cms_category.id', 'cms_category.name');
	}
	
}
