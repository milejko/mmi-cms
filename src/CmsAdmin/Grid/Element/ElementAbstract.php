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
 * 
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 */
abstract class ElementAbstract extends \Mmi\OptionObject {

	/**
	 * Obiekt grida
	 * @var \CmsAdmin\Grid\Grid
	 */
	protected $_grid;

	/**
	 * Konstruktor ustawia nazwę
	 * @param string $name
	 */
	public function __construct($name) {
		$this->setName($name);
	}

	/**
	 * Zwraca nazwę dla elementu formularza
	 * @return string
	 */
	public function getFormElementName() {
		return $this->_grid->getClass() . '[' . $this->getName() . ']';
	}

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
		//brak property
		if (!$this->_fieldInRecord()) {
			return $this->getLabel() ? $this->getLabel() : $this->getName();
		}
		$html = '<a class="order" href="#' . $this->getFormElementName() . '" data-method="' . $this->_getOrderMethod() . '">' . ($this->getLabel() ? $this->getLabel() : $this->getName()) . '</a>';
		//brak sortowania
		if (!$this->_getOrderMethod()) {
			return $html;
		}
		//ikona w dół
		if ($this->_getOrderMethod() == 'orderDesc') {
			return $html . ' <i class="icon-download"></i>';
		}
		//ikona w górę
		return $html . ' <i class="icon-upload"></i>';
	}

	/**
	 * Renderuje filtrację pola
	 * @return string
	 */
	public function renderFilter() {
		//brak property
		if (!$this->_fieldInRecord()) {
			return;
		}
		//zwrot filtra
		return (new \Mmi\Form\Element\Text($this->getFormElementName()))
				->setValue($this->_getFilterValue());
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
	 * Sprawdza istnienie pola w rekordzie
	 * @return boolean
	 */
	protected function _fieldInRecord() {
		return property_exists($this->_grid->getQuery()->getRecordName(), $this->getName());
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
