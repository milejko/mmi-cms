<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

class JsTreeNode extends \Mmi\Mvc\ViewHelper\HelperAbstract
{

    /**
     * Nazwa sztucznego korzenia
     */
    CONST ROOT = '';

    /**
     * Renderuje fragment drzewka pod obsługę przez plugin jsTree
     * @param array $tree drzewiasta struktura
     * @param boolean $root czy otoczyć sztucznym korzeniem
     * @return string
     */
    public function jsTreeNode($tree, $root = false)
    {
        if ($root) {
            //obudowujemy sztucznym rootem
            $html = '<ul><li id="0" data-jstree=\'{"type":"root", "opened":true, "selected":false}\'>' . self::ROOT;
            $html = $this->_generateNode(['children' => $tree], $html);
            $html .= '</li></ul>';
        } else {
            $html = $this->_generateNode(['children' => $tree], '');
        }
        return $html;
    }

    /**
     * Generuje fragment drzewka
     * @param array $node
     * @param string $html
     * @return string
     */
    private function _generateNode($node, $html)
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
            if (!$child['record']->active) {
                $icon = $this->view->baseUrl . '/resource/cmsAdmin/images/folder-inactive.png';
            }
            $selected = 'false';
            $disabled = 'false';
            //sprawdzenie uprawnień do węzła
            if (!$acl->isAllowed(\App\Registry::$auth->getRoles(), $child['record']->id)) {
                $disabled = 'true';
                $icon = $this->view->baseUrl . '/resource/cmsAdmin/images/folder-disabled.png';
            }
            $type = 'default';
            if (!isset($child['children']) || !count($child['children'])) {
                $type = 'leaf';
            }
            $html .= '<li id="' . $child['record']->id . '" class="' . (($type !== 'leaf') ? 'jstree-closed' : '') . '"';
            $html .= ' data-jstree=\'{"type":"' . $type . '"' . (($icon) ? ', "icon":"' . $icon . '"' : '');
            $html .= ', "disabled":' . $disabled . ', "selected":' . $selected . '}\'>' . $child['record']->name . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

}
