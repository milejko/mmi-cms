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
 * Klasa elementu select
 * @method array getMultiOptions()
 */
class SelectElement extends ElementAbstract {

	/**
	 * Ustawia opcje selecta
	 * @param array $options
	 * @return SelectElement
	 */
	public function setMultiOptions(array $options = []) {
		return $this->setOption('multiOptions', $options);
	}
	
	/**
	 * Pobiera opcję po kluczu
	 * @param string $key
	 * @return string
	 */
	public function getMultiOptionByKey($key) {
		$multiOptions = $this->getMultiOptions();
		//wyszukiwanie w multiopcjach
		return isset($multiOptions[$key]) ? $multiOptions[$key] : null;
	}

	/**
	 * Renderuje filtrację pola
	 * @return string
	 */
	public function renderFilter() {
		//tworzy element form selecta, ustawia opcje i wartość filtra
		return (new \Mmi\Form\Element\Select($this->getName()))
				->setMultiOptions($this->getMultiOptions())
				->setValue($this->_getFilterValue());
	}

	/**
	 * Renderuje pole tekstowe
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	public function renderCell(\Mmi\Orm\RecordRo $record) {
		//brak pola
		if (!property_exists($record, $this->getName())) {
			return '?';
		}
		//zwrot z mapy opcji
		return $this->getMultiOptionByKey($record->{$this->getName()});
	}

}
