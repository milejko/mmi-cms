<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Mvc\ViewHelper;

class JsTree extends \Mmi\Mvc\ViewHelper\HelperAbstract {
	
	/**
	 * Nazwa sztucznego korzenia
	 */
	CONST ROOT = 'Korzeń';

	/**
	 * Renderuje drzewko pod obsługę przez plugin jsTree
	 * @param array $tree drzewiasta struktura
	 * @param string $jsPath ścieżka do skryptu JS obsługującego akcje na drzewku
	 * @param string $cssPath ścieżka do dodatkowego pliku CSS
	 * @return string
	 */
	public function jsTree($tree, $jsPath = '', $cssPath = '') {
		//powołanie widoku
		$view = \Mmi\App\FrontController::getInstance()->getView();
		//dołączenie CSS i JavaScriptów
		$view->headLink()->appendStylesheet($view->baseUrl . '/resource/cmsAdmin/js/jstree/themes/default/style.min.css');
		$view->headScript()->prependFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/jstree/jstree.min.js');
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
	private function _getHtmlTree($tree) {
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
	private function _generateTree($node, $html) {
		//jeżeli nie ma węzłów z dzieciakami, to zwracam pusty html
		if (!isset($node['children']) || !is_array($node['children']) || count($node['children']) == 0) {
			return $html;
		}
		$html .= '<ul>';
		//iteracja po dzieciakach i budowa węzłów drzewa
		foreach ($node['children'] as $child) {
			$select = 'false';
			$disabled = 'false';
			$type = ($child['record']->active)? 'default' : 'inactive';
			$html .= '<li id="' . $child['record']->id . '"';
			$html .= ' data-jstree=\'{"type":"' . $type . '", "disabled":' . $disabled . ', "selected":' . $select . '}\'>' . $child['record']->name;
			$html = self::_generateTree($child, $html);
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}

}
