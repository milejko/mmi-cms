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
 * Klasa elementu indeksującego
 * @method IndexElement setIndex($index) ustawia index
 * @method integer getIndex() pobiera wartość indeksu
 */
class IndexElement extends ElementAbstract {
	
	/**
	 * Ustawienia domyślne
	 */
	public function __construct() {
		 $this->setLabel('lp.');
	}
	
	/**
	 * Renderuje filtrację pola
	 * @return string
	 */
	public function renderFilter() {
		return '';
	}
	
	/**
	 * Renderuje labelkę
	 * @return string
	 */
	public function renderLabel() {
		return $this->getLabel();
	}

	/**
	 * Renderuje pole tekstowe
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	public function renderCell(\Mmi\Orm\RecordRo $record) {
		//inicjalizacja
		if ($this->getIndex() === null) {
			//ustawia wartość domyślną uwzględniając paginator
			$this->setIndex(($this->_grid->getState()->getPage() -1) * $this->_grid->getState()->getRowsPerPage());
		}
		//podwyższa indeks
		$this->setIndex($this->getIndex() + 1);
		return $this->getIndex();
	}

}
