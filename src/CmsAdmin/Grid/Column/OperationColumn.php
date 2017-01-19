<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;
use Mmi\App\FrontController;

/**
 * Klasa Columnu indeksującego
 * 
 * @method IndexColumn setIndex($index) ustawia index
 * @method integer getIndex() pobiera wartość indeksu
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 * 
 * @method self setFilterMethodEquals() ustawia metodę filtracji na równość
 * @method self setFilterMethodLike() ustawia metodę filtracji na podobny
 * @method self setFilterMethodSearch() ustawia metodę filtracji na wyszukaj
 * @method self setFilterMethodBetween() ustawia metodę filtracji na pomiędzy

 */
class OperationColumn extends ColumnAbstract {

	/**
	 * Konstruktor ustawia domyślny label
	 * pole bez nazwy
	 */
	public function __construct() {
		//ustawia domyślne parametry
		$this->setLabel('operacje')
			->setEditParams()
			->setDeleteParams();
		//ustawia nazwę na _operation_
		parent::__construct('_operation_');
	}
	
	/**
	 * Ustawia parametry linku edycyjnego
	 * ['action' => 'edit', 'id' => '%id%']
	 * %pole% zastępowany jest przez $record->pole
	 * 
	 * @param array $params
	 * @return OperationColumn
	 */
	public function setEditParams(array $params = ['action' => 'edit', 'id' => '%id%']) {
		return $this->setOption('editParams', $params);
	}
	
	/**
	 * Ustawia parametry linku usuwającego
	 * ['action' => 'delete', 'id' => '%id%']
	 * %pole% zastępowany jest przez $record->pole
	 * 
	 * @param array $params
	 * @return OperationColumn
	 */
	public function setDeleteParams(array $params = ['action' => 'delete', 'id' => '%id%']) {
		return $this->setOption('deleteParams', $params);
	}
	
	/**
	 * Ustawia parametry linku usuwającego
	 * ['action' => 'delete', 'id' => '%id%']
	 * %pole% zastępowany jest przez $record->pole
	 * 
	 * @param array $params
	 * @return OperationColumn
	 */
	public function setDeleteTagParams(array $params = ['action' => 'delete', 'id' => '%id%']) {
		return $this->setOption('deleteTagParams', $params);
	}

	/**
	 * Dodaje dowolny button
	 * @param string $iconName
	 * @param array $params parametry
	 * @return OperationColumn
	 */
	public function addCustomButton($iconName, array $params = []) {
		$customButtons = is_array($this->getOption('customButtons')) ? $this->getOption('customButtons') : [];
		$customButtons[] = ['iconName' => $iconName, 'params' => $params];
		return $this->setOption('customButtons', $customButtons);
	}

	/**
	 * Renderuje komórkę
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	public function renderCell(\Mmi\Orm\RecordRo $record) {
	    	    
		$view = FrontController::getInstance()->getView();
		$html = '';
		//pobieranie parametrów linku edycji
		$editParams = $this->getOption('editParams');
		//pobieranie parametrów linku usuwania
		$deleteParams = $this->getOption('deleteParams');
		//pobieranie parametrów linku usuwania
		$deleteTagParams = $this->getOption('deleteTagParams');
		//przyciski dodatkowe
		$customButtons = $this->getOption('customButtons');
		//przyciski dodatkowe
		if (!empty($customButtons)) {
			//iteracja po przyciskach
			foreach ($customButtons as $button) {
				//brak uprawnień w ACL
				if (!$this->_checkAcl($params = $this->_parseParams($button['params'], $record))) {
					continue;
				}
				//html przycisku
				$html .= '<a href="' . $view->url($params) . '"><i class="icon-' . $button['iconName'] . '"></i></a>&nbsp;&nbsp;';
			}
		}
		//link edycyjny ze sprawdzeniem ACL
		if (!empty($editParams) && $this->_checkAcl($params = $this->_parseParams($editParams, $record))) {
			$html .= '<a href="' . $view->url($params) . '"><i class="icon-pencil"></i></a>&nbsp;&nbsp;';
		}
		//link kasujący ze sprawdzeniem ACL
		if (!empty($deleteParams) && $this->_checkAcl($params = $this->_parseParams($deleteParams, $record))) {
			$html .= '<a href="' . $view->url($params) . '" title="Czy na pewno usunąć" class="confirm"><i class="icon-remove-circle"></i></a>&nbsp;&nbsp;';
		}
		//link kasujący tag
		if (!empty($deleteTagParams)) {
		    if ($record->getJoined('cms_tag_relation')->id) {
			$html .= '<a href="' . $view->url($this->_parseParams($deleteTagParams, $record)) . '" title="Tag jest przypisany do zasobu. Jeżeli zostanie usunięty nie ma możliwości przywrócenia relacji. Czy na pewno usunąć" class="confirm red"><i class="icon-remove-circle"></i></a>&nbsp;&nbsp;';
		    }
		    if (!$record->getJoined('cms_tag_relation')->id) {
			$html .= '<a href="' . $view->url($this->_parseParams($deleteTagParams, $record)) . '" title="Czy na pewno usunąć" class="confirm"><i class="icon-remove-circle"></i></a>&nbsp;&nbsp;';
		    }
		}		
		return $html;
	}
	
	/**
	 * Zwraca tablicę sparsowanych parametrów do linku
	 * @param array $params
	 * @param \Mmi\Orm\RecordRo $record
	 * @return array
	 */
	protected function _parseParams(array $params, \Mmi\Orm\RecordRo $record) {	    
		//inicjalizacja parametrów
		$parsedParams = [];
		$matches = [];
		//iteracja po parametrach
		foreach ($params as $key => $param) {
			//parametr %pole%
			if (preg_match('/%([a-zA-Z]+)%/', $param, $matches)) {
				$parsedParams[$key] = $record->{$matches[1]};
				continue;
			}
			//w pozostałych przypadkach przepisanie parametru
			$parsedParams[$key] = $param;
		}
		return $parsedParams;
	}
	
	/**
	 * Sprawdzenie ACL
	 * @param array $params
	 * @return boolean
	 */
	protected function _checkAcl(array $params) {
		//łączenie parametrów z requestem Front Controllera
		$urlParams = array_merge(FrontController::getInstance()->getRequest()->toArray(), $params);
		//sprawdzenie acl
		return \App\Registry::$acl->isAllowed(\App\Registry::$auth->getRoles(), strtolower($urlParams['module'] . ':' . $urlParams['controller'] . ':' . $urlParams['action']));
	}

}
