<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsTagQuery,
	Cms\Orm\CmsTagRecord,
	Cms\Orm\CmsTagRelationQuery,
	Cms\Orm\CmsTagRelationRecord;

class TagModel {

	/**
	 * Taguje tagiem po nazwie
	 * @param string $tag tag
	 * @param string $object obiekt
	 * @param int $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function addTag($tag, $object, $objectId = null) {
		//filtrowanie tagu
		$filteredTag = (new \Mmi\Filter\Alnum)->filter($tag);
		//kreacja tagu jeśli brak
		if (null === $tagRecord = (new CmsTagQuery)
				->whereTag()->equals($filteredTag)
				->findFirst()) {
			$tagRecord = new CmsTagRecord;
			$tagRecord->tag = $filteredTag;
			$tagRecord->save();
		}
		//wyszukiwanie relacji
		$relationRecord = (new CmsTagRelationQuery)
			->whereCmsTagId()->equals($tagRecord->id)
			->andFieldObject()->equals($object)
			->andFieldObjectId()->equals($objectId)
			->findFirst();
		//znaleziona relacja - nic do zrobienia
		if (null !== $relationRecord) {
			return true;
		}
		//tworzenie relacji
		$newRelationRecord = new CmsTagRelationRecord;
		$newRelationRecord->cmsTagId = $tagRecord->id;
		$newRelationRecord->object = $object;
		$newRelationRecord->objectId = $objectId;
		//zapis
		return $newRelationRecord->save();
	}

	/**
	 * Usuwa tag
	 * @param string $tag tag
	 * @param string $object obiekt
	 * @param int $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function removeTag($tag, $object, $objectId = null) {
		//brak tagu - nic do zrobienia
		if (null === $tagRecord = (new CmsTagQuery)
				->whereTag()->equals($tag)) {
			return false;
		}
		//wyszukiwanie relacji
		if (null === $relationRecord = (new CmsTagRelationQuery)
			->whereCmsTagId()->equals($tagRecord->id)
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
	 * 
	 * @param array $tags
	 * @param string $object
	 * @param int $objectId
	 * @return boolean
	 */
	public static function setTags(array $tags, $object, $objectId = null) {
		//czyszczenie relacji
		(new CmsTagRelationQuery)
			->whereObject()->equals($object)
			->andFieldObjectId()->equals($objectId)
			->find()
			->delete();
		//dodawanie tagów
		foreach ($tags as $tag) {
			self::addTag($tag, $object, $objectId);
		}
		return true;
	}
	
	public static function getTags($object, $objectId) {
		return (new CmsTagRelationQuery)
			->join('cms_tag')->on('cms_tag_id')
			->whereObject()->equals($object)
			->andFieldObjectId()->equals($objectId)
			->findPairs('cms_tag.id', 'cms_tag.tag');
	}
	
}
