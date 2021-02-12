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
    private array $orderMap = [];

    /**
     * Konstruktor pobiera kategorie i buduje drzewo
     */
    public function __construct(CmsCategoryQuery $query)
    {
        //pobieranie kategorii
        $categories = $query
            ->andFieldStatus()->equals(CmsCategoryRecord::STATUS_ACTIVE)
            ->findFields(['id', 'template', 'name', 'uri', 'blank', 'customUri', 'redirectUri', 'path', 'order', 'active']);
        $this->_categoryTree = $this->buildTree($categories);
    }

    private function buildTree($categories)
    {
        $menu = [];
        foreach ($categories as $item) {
            $this->orderMap[$item['id']] = $item['order'] . '-' . $item['id'];
        }
        foreach ($categories as $item) {
            $item['children'] = [];
            $this->injectIntoMenu($menu, $item);
        }
        return $this->sortMenu($menu['children']);
    }

    protected function sortMenu(array $menu): array
    {
        $orderedMenu = [];
        ksort($menu);
        foreach ($menu as $item) {
            if (!empty($item['children'])) {
                $item['children'] = $this->sortMenu($item['children']);
            }
            $orderedMenu[] = $item;
        }
        return $orderedMenu;
    }

    protected function injectIntoMenu(&$menu, $item): void
    {
        foreach (explode('/', trim($item['path'] . '/' . $item['id'], '/')) as $id) {
            $menu = &$menu['children'][isset($this->orderMap[$id]) ? $this->orderMap[$id] : '0-' . $id];
        }
        $menu = array_merge($item, $menu ? : []);
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
