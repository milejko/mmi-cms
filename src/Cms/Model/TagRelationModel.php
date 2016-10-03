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

/**
 * Model relacji tagów
 */
class TagRelationModel {
	
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
	 * Taguje tagiem po nazwie
	 * @param string $tag tag
	 */
	public function createTagRelation($tag) {
		//filtrowanie tagu
		$filteredTag = (new \Mmi\Filter\Input)->filter($tag);
		
		//czy nowy wpisany
		$new = (substr($filteredTag,0,5) === '#add#')?true:false;
		
		//kreacja tagu jeśli brak
		if ((null === $tagRecord = (new CmsTagQuery)
			->whereTag()->equals($filteredTag)
			->findFirst()) && !$new) {
		    
			$tagRecord = new CmsTagRecord;
			$tagRecord->tag = $filteredTag;
			$tagRecord->save();
		}
		
		//kreacja jezeli dodany nowy wpisany
		if ($new){
			$filteredTag = substr($filteredTag,5);			
		    
			$tagRecord = new CmsTagRecord;
			$tagRecord->tag = $filteredTag;
			$tagRecord->save();
		}
		
		//znaleziona relacja - nic do zrobienia
		if (null !== (new CmsTagRelationQuery)
				->whereCmsTagId()->equals($tagRecord->id)
				->andFieldObject()->equals($this->_object)
				->andFieldObjectId()->equals($this->_objectId)
				->findFirst()) {
			return;
		}
		
		//tworzenie relacji
		$newRelationRecord = new CmsTagRelationRecord;
		$newRelationRecord->cmsTagId = $tagRecord->id;
		$newRelationRecord->object = $this->_object;
		$newRelationRecord->objectId = $this->_objectId;
		//zapis
		$newRelationRecord->save();
	}

	/**
	 * Usuwa tag
	 * @param string $tag tag
	 */
	public function deleteTagRelation($tag) {
		//brak tagu - nic do zrobienia
		if (null === $tagRecord = (new CmsTagQuery)
				->whereTag()->equals($tag)
				->findFirst()) {
			return false;
		}
		//wyszukiwanie relacji
		if (null === $relationRecord = (new CmsTagRelationQuery)
			->whereCmsTagId()->equals($tagRecord->id)
			->andFieldObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->findFirst()) {
			//brak relacji - nic do zrobienia
			return false;
		}
		//usunięcie relacji
		return $relationRecord->delete();
	}

	/**
	 * Usuwa relację tagów
	 */
	public function deleteTagRelations() {
		//czyszczenie relacji
		(new CmsTagRelationQuery)
			->whereObject()->equals($this->_object)
			->andFieldObjectId()->equals($this->_objectId)
			->find()
			->delete();
	}

	/**
	 * Pobiera relacje tagów dla obiektu z id
	 * @return array
	 */
	public function getTagRelations() {
		//pobranie relacji
		return (new CmsTagRelationQuery)
				->join('cms_tag')->on('cms_tag_id')
				->whereObject()->equals($this->_object)
				->andFieldObjectId()->equals($this->_objectId)
				->findPairs('cms_tag.id', 'cms_tag.tag');
	}

}
