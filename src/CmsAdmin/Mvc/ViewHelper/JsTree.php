<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

class JsTree extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * Nazwa sztucznego korzenia
     */
    CONST ROOT = '';

    /**
     * Renderuje drzewko pod obsługę przez plugin jsTree
     * @param array $tree drzewiasta struktura
     * @param string $jsPath ścieżka do skryptu JS obsługującego akcje na drzewku
     * @param string $cssPath ścieżka do dodatkowego pliku CSS
     * @return string
     */
    public function jsTree($tree, $jsPath = '', $cssPath = '')
    {
        //powołanie widoku
        $view = \Mmi\App\FrontController::getInstance()->getView();
        //dołączenie CSS i JavaScriptów
        $view->headLink()->appendStylesheet('/resource/cmsAdmin/js/jstree/themes/default/style.min.css');
        $view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/js/jstree/jstree.min.js');
        //warunkowe dołączenie skryptu sterującego
        if (!empty($jsPath)) {
            $view->headScript()->appendFile($jsPath);
        }
        //warunkowe dołączenie dodatkowego CSS
        if (!empty($cssPath)) {
            $view->headLink()->appendStylesheet($cssPath);
        }
        //generowanie HTML drzewka
        return $this->_getHtmlTree($tree);
    }

    /**
     * Zwraca drzewko danych w postaci html
     * @param array $tree drzewiasta struktura
     * @return string
     */
    private function _getHtmlTree($tree)
    {
        //obudowujemy sztucznym rootem
        $html = '<ul><li id="0" data-jstree=\'{"type":"root", "opened":true, "selected":false}\'>' . self::ROOT;
        $html = $this->_generateTree(['children' => $tree], $html);
        $html .= '</li></ul>';
        return $html;
    }

    /**
     * Generuje fragmenty drzewka
     * @param array $node
     * @param string $html
     * @return string
     */
    private function _generateTree($node, $html)
    {
        //jeżeli nie ma węzłów z dzieciakami, to zwracam pusty html
        if (!isset($node['children']) || !is_array($node['children']) || count($node['children']) == 0) {
            return $html;
        }
        $acl = (new \CmsAdmin\Model\CategoryAclModel)->getAcl();
        $html .= '<ul>';
        //iteracja po dzieciakach i budowa węzłów drzewa
        foreach ($node['children'] as $child) {
            $icon = '';
            if (!$child['active']) {
                $icon = $this->view->baseUrl . '/resource/cmsAdmin/images/folder-inactive.png';
            }
            $selected = 'false';
            $disabled = 'false';
            //sprawdzenie uprawnień do węzła
            if (!$acl->isAllowed(\App\Registry::$auth->getRoles(), $child['id'])) {
                $disabled = 'true';
                $icon = $this->view->baseUrl . '/resource/cmsAdmin/images/folder-disabled.png';
            }
            $type = 'default';
            if (!isset($child['children']) || !count($child['children'])) {
                $type = 'leaf';
            }
            $html .= '<li id="' . $child['id'] . '" class="' . (($type !== 'leaf') ? 'jstree-closed' : '') . '"';
            $html .= ' data-jstree=\'{"type":"' . $type . '"' . (($icon) ? ', "icon":"' . $icon . '"' : '');
            $html .= ', "disabled":' . $disabled . ', "selected":' . $selected . '}\'>' . $child['name'];
            $html = self::_generateTree($child, $html);
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

}
