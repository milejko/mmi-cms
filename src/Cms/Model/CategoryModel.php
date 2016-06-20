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
	Cms\Orm\CmsCategoryRecord;

/**
 * Model kategorii
 */
class CategoryModel {

	/**
	 * Kolekcja kategorii
	 * @var array
	 */
	private $_categoryCollection;

	/**
	 * Drzewo kategorii
	 * @var array
	 */
	private $_categoryTree = [];

	/**
	 * Konstruktor pobiera kategorie
	 */
	public function __construct() {
		//pobranie kategorii
		$this->_categoryCollection = (new CmsCategoryQuery)
			->orderAscOrder()
			->find()
			->toObjectArray();
		$this->_buildRecursive($this->_categoryTree, null);
	}

	/**
	 * 
	 * @param array $tree
	 * @param type $parentId
	 */
	private function _buildRecursive(array &$tree, $parentId = null) {
		/* @var $categoryRecord CmsCategoryRecord */
		foreach ($this->_categoryCollection as $key => $categoryRecord) {
			if ($categoryRecord->parentId == $parentId) {
				$tree[$categoryRecord->id] = $categoryRecord->toArray();
				$tree[$categoryRecord->id]['record'] = $categoryRecord;
				$tree[$categoryRecord->id]['children'] = [];
				$this->_buildRecursive($tree[$categoryRecord->id]['children'], $categoryRecord->id);
			}
		}
	}

	/**
	 * Pobiera listę kategorii
	 * @return array
	 */
	public function getCategoriesFlat() {
		return (new CmsCategoryQuery)
				->findPairs('id', 'name');
	}

}
