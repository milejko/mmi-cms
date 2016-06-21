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
	 * Drzewo kategorii
	 * @var array
	 */
	private $_categoryTree;

	/**
	 * Konstruktor pobiera kategorie i buduje drzewo
	 */
	public function __construct() {
		//ładowanie kategorii z cache
		if (null !== $this->_categoryTree = \App\Registry::$cache->load('cms-category-tree')) {
			return;
		}
		$this->_categoryTree = [];
		$this->_buildTree($this->_categoryTree, (new CmsCategoryQuery)
				->orderAscOrder()
				->find()
				->toObjectArray());
		//zapis cache
		\App\Registry::$cache->save($this->_categoryTree, 'cms-category-tree');
	}
	
	/**
	 * Zwraca drzewo kategorii
	 * @return array
	 */
	public function getCategoryTree() {
		return $this->_categoryTree;
	}

	/**
	 * Pobiera listę kategorii
	 * @return array
	 */
	public function getCategoriesFlat() {
		$flatTree = [];
		//budowanie drzewa
		$this->_buildFlatTree(0, $flatTree, $this->_categoryTree);
		return $flatTree;
	}
	
	public function getBreadcrumbsById($categoryId) {
		
	}

	/**
	 * 
	 * @param integer $level
	 * @param array $flatTree
	 * @param array $categories
	 */
	private function _buildFlatTree($level, array &$flatTree, array $categories) {
		//funkcja prefixu
		$prefix = function ($level) {
			$prefix = '';
			for ($i = 0; $i < $level; $i++) {
				$prefix .= '&nbsp;&nbsp;&nbsp;';
			}
			return $prefix . '&boxur;&gt; ';
		};
		//iteracja po kategoriach
		foreach ($categories as $id => $leaf) {
			//dodanie rekordu z prefixem i nazwą
			$flatTree[$id] = $prefix($level) . $leaf['record']->name;
			//zejście rekurencyjne
			$this->_buildFlatTree($level + 1, $flatTree, $leaf['children']);
		}
	}

	/**
	 * Buduje drzewo rekurencyjnie
	 * @param array $tree
	 * @param integer $parentId
	 */
	private function _buildTree(array &$tree, array $orderedCategories, $parentId = null) {
		/* @var $categoryRecord CmsCategoryRecord */
		foreach ($orderedCategories as $key => $categoryRecord) {
			//niezgodny rodzic
			if ($categoryRecord->parentId != $parentId) {
				continue;
			}
			//usuwanie wykorzystanego rekordu kategorii
			unset($orderedCategories[$key]);
			//zapis do drzewa
			$tree[$categoryRecord->id] = [];
			$tree[$categoryRecord->id]['record'] = $categoryRecord;
			$tree[$categoryRecord->id]['children'] = [];
			//zejście rekurencyjne do dzieci
			$this->_buildTree($tree[$categoryRecord->id]['children'], $orderedCategories, $categoryRecord->id);
		}
	}

}
