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
 * Klasa sortowania w stanie grida
 * @method GridStateOrder setTableName($name) ustawia tabelę dla pola
 * @method string getTableName() pobiera tabelę dla pola
 * @method GridStateOrder setField($field) ustawia pole sortowania
 * @method string getField() pobiera pole sortowania
 * @method string getMethod() pobiera typ sortowania
 */
class GridStateOrder extends \Mmi\OptionObject {
	
	/**
	 * Konstruktor, wartości domyślne
	 */
	public function __construct() {
		$this->setMethod('orderAsc');
	}
	
	/**
	 * Ustawia metodę porządkującą
	 * @param string $method nazwa metody
	 * @return GridStateOrder
	 */
	public function setMethod($method) {
		return $this->setOption('method', $method == 'orderDesc' ? 'orderDesc' : 'orderAsc');
	}
	
}