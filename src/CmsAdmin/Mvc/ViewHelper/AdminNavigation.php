<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

/**
 * Helper nawigatora
 */
class AdminNavigation extends \Mmi\Mvc\ViewHelper\Navigation
{

    /**
     * Separator breadcrumbs
     * @var string
     */
    protected $_separator = '';
    //szablon menu
    const TEMPLATE = 'cmsAdmin/mvc/view-helper/adminNavigation/menu-item';

        /**
     * Buduje breadcrumbs
     * @return \Mmi\Mvc\ViewHelper\Navigation
     */
    protected function _buildBreadcrumbs()
    {
        //obiekt nawigatora niezdefiniowany
        if (null === parent::_getNavigation()) {
            return $this;
        }
        //pobieranie breadcrumbów
        $data = $this->getBreadcrumbsData();
        //błędny format danych
        if (!is_array($data)) {
            return $this;
        }
        //inicjalizacja zmiennych
        $title = [];
        $breadcrumbs = [];
        $descriptions = [];
        $i = 0;
        //iteracja po odwróconej tablicy breadcrumbów
        foreach (array_reverse($data) as $breadcrumb) {
            $i++;
            $breadcrumb['label'] = isset($breadcrumb['label']) ? $this->view->_($breadcrumb['label']) : '';
            //dodawanie breadcrumbów (ostatni nie ma linku)
            $breadcrumbs[] = ('<li class="breadcrumb-item"><a href="' . $breadcrumb['uri'] . '">' . strip_tags($breadcrumb['label']) . '</a></li>');
            //liść wyłączony (poza ostatnim)
            if (isset($breadcrumb['disabled']) && ($i != 1) && $breadcrumb['disabled']) {
                continue;
            }
            //dodawanie tytułu
            $title[] = isset($breadcrumb['title']) ? strip_tags($breadcrumb['title']) : strip_tags($breadcrumb['label']);
            //ustawiony jest tytuł - nie łączymy z poprzednikami
            if (isset($breadcrumb['title'])) {
                break;
            }
        }
        //ustawianie pól
        return $this->setTitle(trim(implode($this->_metaSeparator, $title)))
            //breadcrumby muszą zostać odwrócone
            ->setBreadcrumbs(trim(implode($this->_separator, array_reverse($breadcrumbs))));
    }

    /**
     * Renderuje drzewo
     * @param array $tree drzewo
     * @param int $depth głębokość
     * @return string
     */
    protected function _getHtml($tree, $depth = 0)
    {
        //brak drzewa
        if (empty($tree) || !isset($tree['children'])) {
            return '';
        }
        //pobieranie menu
        $menu = $tree['children'];
        //przygotowanie menu do wyświetlenia: usunięcie niedozwolonych i nieaktywnych elementów
        foreach ($menu as $key => $menuItem) {
            //usuwanie modułu
            if ($menuItem['disabled'] || !$this->_checkAcl($menuItem)) {
                unset($menu[$key]);
            }
        }
        //inicjalizacja zmiennych
        $html = '';
        $index = 0;
        $count = count($menu);
        $childHtml = '';
        //pętla po menu
        foreach ($menu as $menuItem) {
            $menuItem['subMenu'] = '';
            $recurse = true;
            if ($this->_activeBranch && isset($menuItem['active'])) {
                $recurse = $menuItem['active'];
            }
            //jeśli liść ma dzieci i nie osiągnięto maksymalnej głębokości
            if (isset($menuItem['children']) && $depth < $this->_maxDepth && $recurse) {
                //schodzenie rekurencyjne o 1 poziom w dół
                $menuItem['subMenu'] = $this->_getHtml($menuItem, $depth + 1);
                $childHtml .= $menuItem['subMenu'];
            }
            //nadawanie klas html
            $menuItem['class'] = (isset($menuItem['active']) && $menuItem['active']) ? 'active' : '';
            $menuItem['depth'] = $depth;
            //obiekt do widoku
            $this->view->_menuItem = $menuItem;
            //render itemu
            $html .= $this->view->renderTemplate(static::TEMPLATE);
            //podwyższanie licznika
            $index++;
        }
        //jeśli renderowanie od minimalnej głębokości
        if ($this->_minDepth > $depth) {
            return $childHtml;
        }
        //jeśli wyrenderowano HTML
        return $html;
    }

    /**
     * Metoda główna, zwraca swoją instancję
     * @return \Mmi\Mvc\ViewHelper\Navigation
     */
    public function adminNavigation()
    {
        return parent::navigation();
    }
}
