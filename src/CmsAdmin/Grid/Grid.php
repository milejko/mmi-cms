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
 * @method Column\CheckboxColumn addColumnCheckbox($field) dodaje Column checkbox
 * @method Column\CustomColumn addColumnCustom($field) dodaje Column dowolny
 * @method Column\IndexColumn addColumnIndex() dodaje Column indeksujący
 * @method Column\SelectColumn addColumnSelect($field) dodaje Column select
 * @method Column\TextColumn addColumnText($field) dodaje Column tekstowy
 * @method Column\OperationColumn addColumnOperation() dodaje Column operacji na rekordzie
 */
abstract class Grid extends \Mmi\OptionObject {

	/**
	 * Columny grida
	 * @var array
	 */
	protected $_columns = [];

	/**
	 * Obiekt zapytania
	 * @var \Mmi\Orm\Query
	 */
	protected $_query;
	
	/**
	 * Dane
	 * @var \Mmi\Orm\RecordCollection
	 */
	protected $_data;
	
	/**
	 * Stan siatki
	 * @var GridState
	 */
	protected $_state;

	/**
	 * Konstruktor
	 */
	public function __construct(array $options = []) {
		//parametry wejściowe do grida
		$this->setOptions($options);
		//tworzy obiekt stanu
		$this->_state = (new GridState)->setGrid($this);
		//indeks
		$this->addColumnIndex();
		$this->init();
		//obsługa zapytań JSON do grida
		(new GridRequestHandler($this))->handleRequest();
	}

	/**
	 * Inicjalizacja
	 */
	abstract public function init();

	/**
	 * Dodaje Column grida
	 * @param \CmsAdmin\Grid\Column\ColumnAbstract $column
	 * @return Column\ColumnAbstract
	 */
	public final function addColumn(Column\ColumnAbstract $column) {
		//dodawanie Columnu (nazwa unikalna)
		return $this->_columns[$column->getName()] = $column->setGrid($this);
	}

	/**
	 * Pobranie Columnów formularza
	 * @return \CmsAdmin\Grid\Column\ColumnAbstract[]
	 */
	public final function getColumns() {
		return $this->_columns;
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
		if (null !== $this->_data) {
			return $this->_data;
		}
		//aktualizuje zapytanie i pobiera dane
		return $this->_data = $this->getState()
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
			return $e->getMessage() . ' ' . $e->getTraceAsString();
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
		//obsługa addColumn
		if (preg_match('/^addColumn([a-zA-Z0-9]+)/', $name, $matches)) {
			$columnClass = '\\CmsAdmin\\Grid\\Column\\' . $matches[1] . 'Column';
			//dodaje Column
			return $this->addColumn(new $columnClass(isset($params[0]) ? $params[0] : null));
		}
		//obsługa nadrzędnych
		return parent::__call($name, $params);
	}

}
