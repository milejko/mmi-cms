<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;

/**
 * Model kategorii
 */
class CategoryModel
{

    /**
     * Drzewo kategorii
     * @var array
     */
    private $_categoryTree = [];

    /**
     * Konstruktor pobiera kategorie i buduje drzewo
     */
    public function __construct(CmsCategoryQuery $query)
    {
        //pobieranie kategorii
        $categories = $query
            ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->orderAscOrder()
            ->findFields(['id', 'path', 'name', 'template', 'order', 'redirectUri', 'uri', 'customUri', 'active']);
        $this->_categoryTree = $this->buildTree($categories);
    }

    private function buildTree($categories)
    {
        $menu = [];
        foreach ($categories as $item) {
            $fullPath = trim($item['path'] . '/' . $item['id'], '/');
            $item['children'] = [];
            $this->injectIntoMenu($menu, $fullPath, $item);
        }
        return $menu['children'];
    }

    private function injectIntoMenu(&$menu, $path, $value): void
    {
        $ids = explode('/', $path);
        $current = &$menu;
        foreach ($ids as $id) {
            $current = &$current['children'][$id];
        }
        $current = is_array($current) ? array_merge($value, $current) : $value;
    }

    /**
     * Zwraca drzewo kategorii
     * @param integer $parentCategoryId identyfikator kategorii rodzica (opcjonalny)
     * @return array
     */
    public function getCategoryTree($parentCategoryId = null)
    {
        //brak zdefiniowanej kategorii
        if ($parentCategoryId === null) {
            return $this->_categoryTree;
        }
        //wyszukiwanie kategorii
        return $this->searchChildren($this->_categoryTree, $parentCategoryId);
    }

    /**
     * Wyszukiwanie dzieci
     * @param array $categories
     * @param integer $parentCategoryId
     * @return array
     */
    private function searchChildren(array $categories, $parentCategoryId = null)
    {
        //iteracja po kategoriach
        foreach ($categories as $id => $category) {
            if ($id == $parentCategoryId) {
                return $category['children'];
            }
            if ([] !== $child = $this->searchChildren($category['children'], $parentCategoryId)) {
                return $child;
            }
        }
        return [];
    }

}
