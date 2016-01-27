<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid;

use Mmi\App\FrontController;

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
	 * @var \Mmi\Session\SessionSpace
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
	 * @param Grid $grid
	 * @return GridState
	 */
	public function setGrid(Grid $grid) {
		$this->_grid = $grid;
		//przypinanie przestrzeni w sesji
		$this->_space = new \Mmi\Session\SessionSpace($grid->getClass());
		//ustawia opcje z sesji jeśli nie puste
		$spaceArray = $this->_space->toArray();
		if (!empty($spaceArray)) {
			$this->setOptions($spaceArray);
		}
		return $this;
	}

	/**
	 * Dodaje filtr
	 * @param GridStateFilter $filter
	 * @return GridState
	 */
	public function addFilter(GridStateFilter $filter) {
		$filters = $this->getFilters();
		//dodaje filtr
		$filters[$filter->getTableName() . '.' . $filter->getField()] = $filter;
		return $this->setFilters($filters);
	}

	/**
	 * Usuwa filtrację
	 * @param GridStateFilter $filter
	 * @return GridState
	 */
	public function removeFilter(GridStateFilter $filter) {
		$filters = $this->getFilters();
		//usuwa filtr
		unset($filters[$filter->getTableName() . '.' . $filter->getField()]);
		return $this->setFilters($filters);
	}

	/**
	 * Dodaje sortowanie
	 * @param GridStateOrder $order
	 * @return GridState
	 */
	public function addOrder(GridStateOrder $order) {
		$orders = $this->getOrder();
		//dodaje order
		$orders[$order->getTableName() . '.' . $order->getField()] = $order;
		return $this->setOrder($orders);
	}

	/**
	 * Usuwa sortowanie
	 * @param GridStateOrder $order
	 * @return GridState
	 */
	public function removeOrder(GridStateOrder $order) {
		$orders = $this->getOrder();
		//usuwa sortowanie
		unset($orders[$order->getTableName() . '.' . $order->getField()]);
		return $this->setOrder($orders);
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
		//aktualizacja danych
		$this->_grid->getDataCollection();
		return $this->_dataCount;
	}

	/**
	 * Stosuje filtry na zapytaniu
	 * @param \Mmi\Orm\Query $query
	 * @return GridState
	 */
	private function _applyFilters(\Mmi\Orm\Query $query) {
		//iteracja po filtrach
		foreach ($this->getFilters() as $filter) {
			//filtr nie jest prawidłowy
			if (!($filter instanceof GridStateFilter)) {
				throw new GridException('Invalid state filter object');
			}
			//operator równości
			if ($filter->getMethod() == 'equals') {
				$query->andField($filter->getField(), $filter->getTableName())->equals($filter->getValue());
				continue;
			}
			//podobieństwo
			if ($filter->getMethod() == 'like') {
				$query->andField($filter->getField(), $filter->getTableName())->like($filter->getValue() . '%');
				continue;
			}
			//domyślnie - wyszukanie
			$query->andField($filter->getField(), $filter->getTableName())->like('%' . $filter->getValue() . '%');
		}
		return $this;
	}

	/**
	 * Stosuje sortowania na zapytaniu
	 * @param \Mmi\Orm\Query $query
	 * @return GridState
	 */
	private function _applyOrder(\Mmi\Orm\Query $query) {
		$orders = $this->getOrder();
		//resetowanie domyślnego orderu jeśli podany
		if (!empty($orders)) {
			$query->resetOrder();
		}
		//iteracja po orderze
		foreach ($orders as $order) {
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
