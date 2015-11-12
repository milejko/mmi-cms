<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

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
class IndexColumn extends ColumnAbstract {
	
	/**
	 * Konstruktor ustawia domyślny label
	 * pole nie ma nazwy
	 */
	public function __construct() {
		 $this->setLabel('#');
		 parent::__construct('_index_');
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
