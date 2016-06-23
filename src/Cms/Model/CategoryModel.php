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
		//budowanie drzewa z płaskiej struktury orm
		$this->_buildTree($this->_categoryTree, (new CmsCategoryQuery)
				->orderAscOrder()
				->find()
				->toObjectArray());
		//zapis cache
		\App\Registry::$cache->save($this->_categoryTree, 'cms-category-tree');
	}
	
	/**
	 * Zwraca drzewo kategorii
	 * @param integer $parentCategoryId identyfikator kategorii rodzica (opcjonalny)
	 * @return array
	 */
	public function getCategoryTree($parentCategoryId = null) {
		//brak zdefiniowanej kategorii
		if ($parentCategoryId === null) {
			return $this->_categoryTree;
		}
		//wyszukiwanie kategorii
		return $this->_searchChildren($this->_categoryTree, $parentCategoryId);
	}

	/**
	 * Pobiera listę kategorii w postaci płaskiej tabeli z odwzorowaniem drzewa
	 * @param integer $parentCategoryId identyfikator kategorii (opcjonalny)
	 * @return array
	 */
	public function getCategoryFlatTree($parentCategoryId = null) {
		$flatTree = [];
		//budowanie płaskie drzewo
		$this->_buildFlatTree('', $flatTree, $this->getCategoryTree($parentCategoryId));
		return $flatTree;
	}
	
	/**
	 * Pobiera breadcrumby dla 
	 * @param integer $categoryId identyfikator kategorii (opcjonalny)
	 */
	public function getBreadcrumbsById($categoryId) {
		
	}
	
	/**
	 * Wyszukiwanie dzieci
	 * @param array $categories
	 * @param integer $parentCategoryId
	 * @return array
	 */
	private function _searchChildren(array $categories, $parentCategoryId = null) {
		foreach ($categories as $id => $category) {
			if ($id == $parentCategoryId) {
				return $category['children'];
			}
			if (null !== $child = $this->_search($category['children'], $parentCategoryId)) {
				return $child;
			}
		}
	}
	
	/**
	 * Wyszukiwanie rodziców
	 * @param array $categories
	 * @param integer $categoryId
	 * @return array
	 */
	private function _searchParents(array $categories, $categoryId = null) {
		foreach ($categories as $id => $category) {
			if ($id == $categoryId) {
				return $category['children'];
			}
			if (null !== $child = $this->_search($category['children'], $categoryId)) {
				return $child;
			}
		}
	}

	/**
	 * Buduje płaskie drzewo
	 * @param string $prefix
	 * @param array $flatTree
	 * @param array $categories
	 */
	private function _buildFlatTree($prefix, array &$flatTree, array $categories) {
		//iteracja po kategoriach
		foreach ($categories as $id => $leaf) {
			//dodanie rekordu z prefixem i nazwą
			$flatTree[$id] = ltrim($prefix . ' > ' . $leaf['record']->name, ' >');
			//zejście rekurencyjne
			$this->_buildFlatTree($prefix . ' > ' . $leaf['record']->name, $flatTree, $leaf['children']);
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
