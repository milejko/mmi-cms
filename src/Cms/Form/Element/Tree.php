<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element drzewo
 */
class Tree extends \Mmi\Form\Element\ElementAbstract
{
    //szablon początku pola
    public const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    public const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    public const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    public const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    public const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    public function __construct($name)
    {
        parent::__construct($name);
        $this->addFilter(new \Mmi\Filter\EmptyToNull());
    }

    /**
     * Ustawia strukturę drzewka
     * @param array $structure
     * @return \Cms\Form\Element\Tree
     */
    public function setStructure(array $structure)
    {
        $this->setOption('structure', $structure);
        return $this;
    }

    /**
     * Ustawia wielokrotny wybór na drzewku
     * @return \Cms\Form\Element\Tree
     */
    public function setMultiple($multiple = true)
    {
        $this->setOption('multiple', $multiple);
        return $this;
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        //powolanie widoku, CSS i JavaScriptow
        $this->view->headLink()->appendStylesheet('/resource/cmsAdmin/js/jstree/themes/default/style.min.css');
        $this->view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/jstree/jstree.min.js');

        //glowny kontener drzewa
        $html = '<div class="tree_container">';
        $html .= $this->_getHtmlTree();
        $this->unsetOption('structure');
        $html .= '<input type="hidden" ' . $this->_getHtmlOptions() . '/></div>';

        return $html;
    }

    /**
     * Zwraca drzewko danych w postaci html
     * @return string
     */
    private function _getHtmlTree()
    {
        //pobranie struktury
        $structure = $this->getOption('structure');
        //bez struktury zwraca pusty string
        if (!is_array($structure) || empty($structure)) {
            return '';
        }
        //bez dzieci rowniez zwraca pusty string
        if (!isset($structure['children'])) {
            return '';
        }
        //skladam identyfikator galezi
        $treeId = $this->getOption('id') . '_tree';
        //zidentyfikowana gałąź drzewa
        $html = '<div class="tree_structure" id="' . $treeId . '">';
        $html .= $this->_generateTree($structure, '');
        $html .= '</div>';
        $html .= '<button type="button" id="' . $treeId . '_clear" class="tree_clear btn" /> Wyczyść wybór </button>';

        $this->_generateJs($treeId);

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
        //jezeli nie ma wezłów z dzieciakami to zwracam pusty html
        if (!isset($node['children']) || !is_array($node['children']) || count($node['children']) == 0) {
            return $html;
        }
        //zaznaczone wartości
        $values = explode(';', $this->getValue());
        $html .= '<ul>';
        //iteracja po dzieciakach i budowa lisci drzewa
        foreach ($node['children'] as $child) {
            if (isset($child['record'])) {
                $children = isset($child['children']) ? $child['children'] : [];
                $child = $child['record']->toArray();
                $child['children'] = $children;
            }
            $select = 'false';
            if (in_array($child['id'], $values)) {
                $select = 'true';
            }
            $disabled = 'false';
            if (isset($child['allow']) && !$child['allow']) {
                $disabled = 'true';
            }
            $html .= '<li id="' . $child['id'] . '"';
            $html .= ' data-jstree=\'{"type":"default", "disabled":' . $disabled . ', "selected":' . $select . '}\'>' . strip_tags($child['name']);
            $html = self::_generateTree($child, $html);
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Generuje JS do odpalenia drzewka
     * @param string $treeId
     * @return void
     */
    private function _generateJs($treeId)
    {
        $id = $this->getOption('id');
        $treeClearId = $treeId . '_clear';
        $this->view->headScript()->appendScript("$(document).ready(function () {
				$('#$treeId').jstree({
					'core': {
						'themes': {
							'name': 'default',
							'variant': 'small',
							'responsive' : true,
							'stripes' : true
						},
						'multiple': " . ($this->getOption('multiple') ? 'true' : 'false') . ",
						'expand_selected_onload': true,
						'check_callback' : false
					}
				})
				.on('changed.jstree', function (e, data) {
					var selectedStr = '';
					if (0 in data.selected) {
						selectedStr = data.selected[0];
					}
					for (idx = 1, len = data.selected.length; idx < len; ++idx) {
						selectedStr = selectedStr.concat(';' + data.selected[idx])
					}
					$('#$id').val(selectedStr);
				});
				$('#$treeClearId').click(function () {
					$('#$id').val('');
					$('#$treeId').jstree('deselect_all');
				});
			});
		");
    }
}
