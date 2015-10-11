<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;

/**
 * Abstrakcyjna klasa grida
 * 
 * @method Element\CheckboxElement addElementCheckbox($field) dodaje element checkbox
 * @method Element\CustomElement addElementCustom($field) dodaje element dowolny
 * @method Element\IndexElement addElementIndex() dodaje element indeksujący
 * @method Element\SelectElement addElementSelect($field) dodaje element select
 * @method Element\TextElement addElementText($field) dodaje element tekstowy
 * @method Element\OperationElement addElementOperation() dodaje element operacji na rekordzie
 */
abstract class Grid extends \Mmi\OptionObject {

	/**
	 * Elementy grida
	 * @var array
	 */
	protected $_elements = [];

	/**
	 * Obiekt zapytania
	 * @var \Mmi\Orm\Query
	 */
	protected $_query;

	/**
	 * Stan siatki
	 * @var GridState
	 */
	protected $_state;

	/**
	 * Konstruktor
	 */
	public function __construct() {
		//tworzy obiekt stanu
		$this->_state = (new GridState())->setGrid($this);
		$this->init();
		//obsługa zapytań JSON do grida
		(new GridRequestHandler($this))->handleRequest();
	}

	/**
	 * Inicjalizacja
	 */
	abstract public function init();

	/**
	 * Dodaje element grida
	 * @param \CmsAdmin\Grid\Element\ElementAbstract $element
	 * @return Element\ElementAbstract
	 */
	public final function addElement(Element\ElementAbstract $element) {
		//dodawanie elementu (nazwa unikalna)
		return $this->_elements[$element->getName()] = $element->setGrid($this);
	}

	/**
	 * Pobranie elementów formularza
	 * @return \CmsAdmin\Grid\Element\ElementAbstract[]
	 */
	public final function getElements() {
		return $this->_elements;
	}

	/**
	 * Zwraca obiekt stanu
	 * @return GridState
	 */
	public final function getState() {
		return $this->_state;
	}

	/**
	 * Pobiera zapytanie
	 * @return \Mmi\Orm\Query
	 */
	public final function getQuery() {
		//brak obiektu zapytania
		if (!$this->_query) {
			throw new GridException('Query not initialized');
		}
		return $this->_query;
	}

	/**
	 * Ustawia startowe zapytanie filtrujące
	 * @param \Mmi\Orm\Query $query
	 * @return \CmsAdmin\Grid\Grid
	 */
	public final function setQuery(\Mmi\Orm\Query $query) {
		$this->_query = $query;
		return $this;
	}

	/**
	 * Pobiera uproszczoną nazwę klasy grida
	 * @return string
	 */
	public final function getClass() {
		return str_replace('\\', '', get_class($this));
	}

	/**
	 * Pobiera kolekcję rekordów
	 * @return \Mmi\Orm\RecordCollection
	 */
	public final function getDataCollection() {
		//aktualizuje zapytanie i pobiera dane
		return $this->getState()
				->setupQuery($this->getQuery())
				->find();
	}

	/**
	 * Render grida
	 */
	public final function __toString() {
		try {
			//rendering grida HTML
			return (new GridRenderer($this))->render();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Magicznie wywoływanie metod
	 * @param string $name
	 * @param array $params
	 * @return mixed
	 */
	public function __call($name, $params) {
		$matches = [];
		//obsługa addElement
		if (preg_match('/addElement([a-zA-Z0-9]+)/', $name, $matches)) {
			$elementClass = '\\CmsAdmin\\Grid\\Element\\' . $matches[1] . 'Element';
			//dodaje element
			return $this->addElement(new $elementClass(isset($params[0]) ? $params[0] : null));
		}
		//obsługa nadrzędnych
		return parent::__call($name, $params);
	}

}
