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
 * Klasa elementu checkbox
 */
class CheckboxElement extends SelectElement {
	
	/**
	 * Domyślne opcje dla checkboxa
	 */
	public function __construct($name) {
		$this->setMultiOptions([
			null => '---',
			0 => 'odznaczone',
			1 => 'zaznaczone'
		]);
		parent::__construct($name);
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
		//nowy element select
		return (new \Mmi\Form\Element\Checkbox($this->getName()))
			//ustawia wartość na odpowiadającą zaznaczeniu
			->setValue($this->_getCheckedValue())
			//ustawia zaznaczenie
			->setChecked($this->_getCheckedValue() == $record->{$this->getName()});
	}
	
	/**
	 * Określa wartość dla zaznaczonego checkboxa (najwyższa)
	 * @return integer
	 */
	protected function _getCheckedValue() {
		$checked = 0;
		//iteracja po opcjach
		foreach ($this->getMultiOptions() as $option => $caption) {
			$checked = ($option >= $checked) ? $option : $checked;
		}
		return $checked;
	}

}
