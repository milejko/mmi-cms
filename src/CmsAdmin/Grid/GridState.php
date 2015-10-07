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
 * Klasa stanu grida
 * @method GridState setFilters()
 * @method GridStateFilter[] getFilters()
 * @method GridState setOrder(array $order)
 * @method GridStateOrder[] getOrder()
 * @method GridState setPage($page)
 * @method integer getPage()
 * @method GridState setRowsPerPage($page)
 * @method integer getRowsPerPage()
 * 
 */
class GridState extends \Mmi\OptionObject {

	/**
	 * Przestrzeń stanu w sesji
	 * @var \Mmi\Session\Space
	 */
	protected $_space;
	
	/**
	 * Rozmiar danych
	 * @var integer
	 */
	protected $_dataCount = 0;
	
	/**
	 * Obiekt grida
	 * @var Grid
	 */
	protected $_grid;

	/**
	 * Ustawia wartości domyślne
	 */
	public function __construct() {
		//ustawienia domyślne
		$this->setFilters([])
			->setOrder([])
			->setPage(1)
			->setRowsPerPage(20);
	}
	
	/**
	 * Przypina grida
	 * @param \CmsAdmin\Grid\Grid $grid
	 * @return \CmsAdmin\Grid\GridState
	 */
	public function setGrid(Grid $grid) {
		$this->_grid = $grid;
		//przypinanie przestrzeni w sesji
		$this->_space = new \Mmi\Session\Space($class = get_class($grid));
		//ustawia opcje z sesji jeśli nie puste
		if (!empty($this->_space->toArray())) {
			$this->setOptions($this->_space->toArray());
		}
		return $this;
	}
	
	/**
	 * Dodaje filtr
	 * @param \CmsAdmin\Grid\GridStateFilter $filter
	 */
	public function addFilter(GridStateFilter $filter) {
		$filters = $this->getFilters();
		//dodaje filtr
		$filters[] = $filter;
		$this->setFilters($filters);
		return $this;
	}

	/**
	 * Dodaje sortowanie
	 * @param \CmsAdmin\Grid\GridStateOrder $order
	 */
	public function addOrder(GridStateOrder $order) {
		$orders = $this->getOrder();
		//dodaje order
		$orders[] = $order;
		$this->setOrder($orders);
		return $this;
	}
	
	/**
	 * Ustawianie opcji
	 * @param type $key
	 * @param type $value
	 * @return GridState
	 */
	public function setOption($key, $value) {
		//ustawienie na przestrzeni w sesji jeśli zdefiniowana
		$this->_space ? $this->_space->__set($key, $value) : null;
		return parent::setOption($key, $value);
	}
	
	/**
	 * Dekoruje querę na podstawie filtrów
	 * @param \Mmi\Orm\Query $query
	 * @return \Mmi\Orm\Query
	 */
	public function setupQuery(\Mmi\Orm\Query $query) {
		//stosowanie filtrów i sortowań
		$this->_applyFilters($query)
			->_applyOrder($query);
		//ustawia rozmiar danych
		$this->_dataCount = $query->count();
		//obliczenia offsetu
		$offset = ($this->getPage() - 1) * $this->getRowsPerPage();
		//jeśli offset jest nieprawidłowy
		if ($offset > $this->_dataCount) {
			throw new GridException('Invalid offset');
		}
		//limitowanie
		$query->limit($this->getRowsPerPage())
			->offset($offset);
		return $query;
	}
	
	/**
	 * Zwraca rozmiar danych
	 * @return integer
	 */
	public function getDataCount() {
		return $this->_dataCount;
	}
	
	/**
	 * Stosuje filtry na zapytaniu
	 * @param \Mmi\Orm\Query $query
	 * @return \CmsAdmin\Grid\GridState
	 */
	private function _applyFilters(\Mmi\Orm\Query $query) {
		//resetowanie where
		$query->resetWhere();
		foreach ($this->getFilters() as $filter) {
			//filtr nie jest prawidłowy
			if (!($filter instanceof GridStateFilter)) {
				throw new GridException('Invalid state filter object');
			}
			//aplikacja na querę
			$query->andField($filter->getField(), $filter->getTableName())->{$filter->getMethod()}(($filter->getMethod() == 'like' ? '%' . $filter->getValue() . '%' : $filter->getValue()));
		}
		return $this;
	}
	
	/**
	 * Stosuje sortowania na zapytaniu
	 * @param \Mmi\Orm\Query $query
	 * @return \CmsAdmin\Grid\GridState
	 */
	private function _applyOrder(\Mmi\Orm\Query $query) {
		//resetowanie orderu
		$query->resetOrder();
		foreach ($this->getOrder() as $order) {
			//order nie jest obiektem sortowania
			if (!($order instanceof GridStateOrder)) {
				throw new GridException('Invalid state order object');
			}
			//aplikacja na querę
			$query->{$order->getMethod()}($order->getField(), $order->getTableName());
		}
		return $this;
	}

}
