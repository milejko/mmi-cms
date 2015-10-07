<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Element;

/**
 * Abstrakcyjna klasa elementu
 * @method ElementAbstract setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method ElementAbstract setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 */
abstract class ElementAbstract extends \Mmi\OptionObject {

	/**
	 * Obiekt grida
	 * @var \CmsAdmin\Grid\Grid
	 */
	protected $_grid;

	/**
	 * Ustawia grida macieżystego
	 * @param \CmsAdmin\Grid\Grid $grid
	 * @return ElementAbstract
	 */
	public function setGrid(\CmsAdmin\Grid\Grid $grid) {
		$this->_grid = $grid;
		return $this;
	}
	
	/**
	 * Renderuje labelkę kolumny
	 * @return string
	 */
	public function renderLabel() {
		return '<a href="#' . $this->getName() . '" class="' . $this->_getOrderMethod() . '">' . ($this->getLabel() ? $this->getLabel() : $this->getName()) . '</a>';
	}

	/**
	 * Renderuje filtrację pola
	 * @return string
	 */
	public function renderFilter() {
		return '<input type="text" value="' . $this->_getFilterValue() . '"/>';
	}

	/**
	 * Renderuje komórkę pola dla danego rekordu
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	abstract public function renderCell(\Mmi\Orm\RecordRo $record);

	/**
	 * Zwraca filtr dla pola
	 * @return string
	 */
	protected function _getFilterValue() {
		//iteracja po filtrache w gridzie
		foreach ($this->_grid->getState()->getFilters() as $filter) {
			//znaleziony filtr dla tego pola
			if ($filter->getField() == $this->getName()) {
				//zwrot wartości filtra
				return $filter->getValue();
			}
		}
	}

	/**
	 * Zwraca sortowanie dla pola
	 * @return string
	 */
	protected function _getOrderMethod() {
		//iteracja po sortowaniach w gridzie
		foreach ($this->_grid->getState()->getOrder() as $order) {
			//znalezione sortowanie tego pola
			if ($order->getField() == $this->getName()) {
				//zwrot metody sortowania
				return $order->getMethod();
			}
		}
	}

}
