<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Grid\Column;

/**
 * Klasa Columnu tekstowego
 * 
 * @method self setName($name) ustawia nazwę pola
 * @method string getName() pobiera nazwę pola
 * @method self setLabel($label) ustawia labelkę
 * @method string getLabel() pobiera labelkę
 * 
 * @method self setFilterMethodEquals() ustawia metodę filtracji na równość
 * @method self setFilterMethodLike() ustawia metodę filtracji na podobny
 * @method self setFilterMethodSearch() ustawia metodę filtracji na wyszukaj
 * @method self setFilterMethodNull() ustawia metodę filtracji na równe/różne null
 */
class JsonColumn extends ColumnAbstract {

	/**
	 * Renderuje pole tekstowe
	 * @param \Mmi\Orm\RecordRo $record
	 * @return string
	 */
	public function renderCell(\Mmi\Orm\RecordRo $record) {
		//dekodowanie json
		if (null === $jsonData = json_decode($this->getValueFromRecord($record), true)) {
			return;
		}
		//zwrot html pola
		return '<pre>' . $this->_renderRecursive($jsonData) .'</pre>';
	}
	
	/**
	 * Renderowanie rekursywne
	 * @param array $jsonData
	 * @param wcięcie $indent
	 * @return string
	 */
	protected function _renderRecursive(array $jsonData, $indent = 0) {
		$html = '';
		//iteracja po danych
		foreach ($jsonData as $field => $value) {
			//generowanie wcięcia
			for ($i = 0; $i < $indent; $i++) {
				$html .= "\t";
			}
			//budowanie html
			$html .= (is_array($value) ? 
				($field . ': ' . $this->_renderRecursive($value, ++$indent)) : 
				($field . ': ' . $this->_filterField($value)))
				. "\n";
		}
		//zwrot html
		return $html;
	}
	
	/**
	 * Filtrowanie pojedynczej wartości
	 * @param string $fieldValue
	 * @return string
	 */
	protected function _filterField($fieldValue) {
		//usuwanie html i obcinanie zbyt długich ciągów
		return (new \Mmi\Filter\Truncate)->filter((new \Mmi\Filter\Escape)->filter($fieldValue));
	}

}
