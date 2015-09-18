<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;
use \Cms\Orm;

class Tag {

	/**
	 * Taguje tagiem po identyfikatorze
	 * @param int $tagId identyfikator taga
	 * @param string $object obiekt
	 * @param int $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function tag($tagId, $object, $objectId = null) {
		try {
			$record = new Orm\Tag\Link\Record();
			$record->cmsTagId = $tagId;
			$record->object = $object;
			$record->objectId = $objectId;
			return $record->save();
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Taguje tagiem po nazwie
	 * @param string $tagName nazwa tagu
	 * @param string $object obiekt
	 * @param int $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function namedTag($tagName, $object, $objectId = null) {
		$tag = Orm\Tag\Query::byName(trim($tagName))
			->findFirst();
		if ($tag === null) {
			return false;
		}
		return self::tag($tag->id, $object, $objectId);
	}

	/**
	 * Usuwa tag po id
	 * @param int $tagId identyfikator taga
	 * @param string $object obiekt
	 * @param int $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function unTag($tagId, $object, $objectId = null) {
		return Orm\Tag\Link\Query::factory()
				->whereCmsTagId()->equals($tagId)
				->andFieldObject()->equals($object)
				->andFieldObjectId()->equals($objectId)
				->find()
				->delete();
	}

	/**
	 * Usuwa tag po nazwie
	 * @param string $tagName nazwa tagu
	 * @param string $object obiekt
	 * @param integer $objectId identyfikator obiektu
	 * @return boolean
	 */
	public static function unNamedTag($tagName, $object, $objectId = null) {
		$tag = Orm\Tag\Query::byName(trim($tagName))
			->findFirst();
		if ($tag === null) {
			return false;
		}
		return self::unTag($tag->id, $object, $objectId);
	}

	/**
	 * Czyszczenie tagów
	 * @param string $object obiekt
	 * @param int $objectId id obiektu
	 * @return int ilość usuniętych
	 */
	public static function clearTags($object, $objectId = null) {
		return Orm\Tag\Link\Query::factory()
				->whereObject()->equals($object)
				->andFieldObjectId()->equals($objectId)
				->find()
				->delete();
	}

	/**
	 * Zamiana tagów na podstawie tablicy identyfikatorów
	 * @param array $tags tablica identyfikatorów tagów
	 * @param string $object obiekt
	 * @param int $objectId id obiektu
	 * @return boolean
	 */
	public static function replaceTags(array $tagIds, $object, $objectId = null) {
		self::clearTags($object, $objectId);
		$result = true;
		foreach ($tagIds as $tagId) {
			$result = $result && self::tag($tagId, $object, $objectId);
		}
		return $result;
	}

	/**
	 * Zmiana tagów na podstawie tablicy nazw tagów
	 * @param array $tagNames
	 * @param string $object
	 * @param integer $objectId
	 * @return boolean
	 */
	public static function replaceNamedTags(array $tagNames, $object, $objectId = null) {
		$tagIds = [];
		foreach ($tagNames as $tagName) {
			$tag = Orm\Tag\Query::byName(trim($tagName))
				->findFirst();
			//tworzy tag jeśli jeszcze nie utworzony
			if ($tag == null) {
				$tag = new Record();
				$tag->tag = $tagName;
				$tag->save();
			}
			$tagIds[] = $tag->id;
		}
		return self::replaceTags($tagIds, $object, $objectId);
	}

	/**
	 * 
	 * @param string $object
	 * @param integer $objectId
	 * @return string
	 */
	public static function getTagString($object, $objectId) {
		$tagString = '';
		foreach (Orm\Tag\Link\Query::tagsByObject($object, $objectId)->find() as $tag) {
			$tagString .= $tag->getJoined('cms_tag')->tag . ',';
		}
		return trim($tagString, ', ');
	}

}
