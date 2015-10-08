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
	 * @return Grid
	 */
	public final function addElement(Element\ElementAbstract $element) {
		//dodawanie elementu (nazwa unikalna)
		$this->_elements[$element->getName()] = $element->setGrid($this);
		return $this;
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

}
