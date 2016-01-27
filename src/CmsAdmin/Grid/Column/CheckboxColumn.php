<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

/**
 * Klasa Columnu checkbox
 * 
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
class CheckboxColumn extends SelectColumn {
	
	/**
	 * Domyślne opcje dla checkboxa
	 */
	public function __construct($name) {
		$this->setMultioptions([
			0 => 'odznaczone',
			1 => 'zaznaczone'
		]);
		parent::__construct($name);
	}
	
	/**
	 * Ustawia grid
	 * @param \CmsAdmin\Grid\Grid $grid
	 * @return \CmsAdmin\Grid\Column\CheckboxColumn
	 */
	public function setGrid(\CmsAdmin\Grid\Grid $grid) {
		parent::setGrid($grid);
		//obsługa zapisu rekordu
		(new CheckboxRequestHandler($this))->handleRequest();
		//zwrot siebie
		return $this;
	}

	/**
	 * Renderuje pole tekstowe
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	public function renderCell(\Mmi\Orm\RecordRo $record) {
		//brak pola
		if (!$this->_fieldInRecord()) {
			return '?';
		}
		//nowy Column select
		return (new \Mmi\Form\Element\Checkbox($this->getFormColumnName()))
			//ustawia wartość na odpowiadającą zaznaczeniu
			->setValue($this->_getCheckedValue())
			->setId($this->getFormColumnName() . '-' . $record->id)
			//ustawia zaznaczenie
			->setChecked($this->_getCheckedValue() == $this->getValueFromRecord($record));
	}
	
	/**
	 * Określa wartość dla zaznaczonego checkboxa (najwyższa)
	 * @return integer
	 */
	protected function _getCheckedValue() {
		$checked = 0;
		//iteracja po opcjach
		foreach ($this->getMultioptions() as $option => $caption) {
			$checked = ($option >= $checked) ? $option : $checked;
		}
		return $checked;
	}

}
