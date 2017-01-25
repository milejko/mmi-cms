<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element drzewo
 * 
 * Metody add
 * @method self addClass($className) dodaje klasę HTML
 * @method self addFilter(\Mmi\Filter\FilterAbstract $filter) dodaje filtr
 * @method self addValidator(\Mmi\Validator\ValidatorAbstract $validator) dodaje walidator
 * @method self addError($error) dodaje błąd
 * 
 * Settery
 * @method self setName($name) ustawia nazwę
 * @method self setValue($value) ustawia wartość
 * @method self setId($id) ustawia identyfikator
 * @method self setPlaceholder($placeholder) ustawia placeholder pola
 * @method self setDescription($description) ustawia opis
 * @method self setIgnore($ignore = true) ustawia ignorowanie
 * @method self setDisabled($disabled = true) ustawia wyłączone
 * @method self setReadOnly($readOnly = true) ustawia tylko do odczytu
 * @method self setLabel($label) ustawia labelkę
 * @method self setRequiredAsterisk($asterisk = '*') ustawia znak gwiazdki
 * @method self setRequired($required = true) ustawia wymagalność
 * @method self setLabelPostfix($labelPostfix) ustawia postfix labelki
 * @method self setForm(\Mmi\Form\Form $form) ustawia formularz
 * @method self setDateMin($dateMin) ustawia datę minimalną
 * @method self setDateMax($dateMax) ustawia datę maksymalną
 * @method self setDatepicker($datepicker) ustawia włączone wybieranie daty
 * @method self setTimepicker($timepicker) ustawia włączone wybieranie godziny
 * @method self setDateMinField(\Cms\Form\Element\DateTimePicker $field) ustawia pole limitujące datę od dołu
 * @method self setDateMaxField(\Cms\Form\Element\DateTimePicker $field) ustawia pole limitujące datę od góry
 * @method self setStep($step) ustawia krok w godzinach
 * 
 * Gettery
 * @method string getDateMin() pobiera datę minimalną
 * @method string getDateMax() pobiera datę maksymalną
 * @method boolean getDatepicker() pobiera możliwość wybrania daty
 * @method boolean getTimepicker() pobiera możliwość wybrania godziny
 * @method \Cms\Form\Element\DateTimePicker getDateMinField() pobiera pole limitujące datę od dołu
 * @method \Cms\Form\Element\DateTimePicker getDateMaxField() pobiera pole limitujące datę od góry
 * @method string getStep() pobiera krok w godzinach
 * 
 * Walidatory
 * @method self addValidatorAlnum($message = null) walidator alfanumeryczny
 * @method self addValidatorDate($message = null) walidator daty
 * @method self addValidatorEmailAddress($message = null) walidator email
 * @method self addValidatorEmailAddressList($message = null) walidator listy email
 * @method self addValidatorEqual($value, $message = null) walidator równości
 * @method self addValidatorIban($country = null, $message = null) walidator IBAN
 * @method self addValidatorInteger($message = null) walidator liczb całkowitych
 * @method self addValidatorIp4($message = null) walidator IPv4
 * @method self addValidatorIp6($message = null) walidator IPv6
 * @method self addValidatorNotEmpty($message = null) walidator niepustości
 * @method self addValidatorNumberBetween($from, $to, $message = null) walidator numer pomiędzy
 * @method self addValidatorNumeric($message = null) walidator numeryczny
 * @method self addValidatorPostal($message = null) walidator kodu pocztowego
 * @method self addValidatorRecordUnique(\Mmi\Orm\Query $query, $field, $id = null, $message = null) walidator unikalności rekordu
 * @method self addValidatorRegex($pattern, $message = null) walidator regex
 * @method self addValidatorStringLength($message = null) walidator długości ciągu
 * 
 * Filtry
 * @method self addFilterAlnum() filtr alfanumeryczny
 * @method self addFilterAscii() filtr ASCII
 * @method self addFilterCapitalize() filtr kapitalizacja
 * @method self addFilterCeil() filtr sufit
 * @method self addFilterCount() filtr zliczający
 * @method self addFilterDateFormat($format) filtr formatujący datę
 * @method self addFilterDump() filtr zrzucający
 * @method self addFilterEmptyToNull() filtr konwertujący pustą wartość do null
 * @method self addFilterEscape() filtr wykluczający HTML
 * @method self addFilterInput() filtr tekstowy
 * @method self addFilterIntval() filtr konwertujący do liczby całkowitej
 * @method self addFilterIsEmpty() filtr sprawdzający pustość
 * @method self addFilterLength() filtr długości zmiennej
 * @method self addFilterLowercase() filtr obniżający litery
 * @method self addFilterMarkupProperty() filtr wycina znaki do poprawnych dla atrybutu HTML
 * @method self addFilterNl2Br() filtr nowa linia do br
 * @method self addFilterNumberFormat($digits, $separator, $thousands = null, $trimZeros = null, $trimZerosLeave = null) filtr format liczby
 * @method self addFilterReplace($search, $replace) filtr zamiana
 * @method self addFilterRound($precision) filtr zaokrąglenie z precyzją
 * @method self addFilterStringTrim($extras) filtr trim
 * @method self addFilterStripTags($exceptions) filtr usuwanie tagów HTML
 * @method self addFilterTinyMce() filtr dla tinyMce
 * @method self addFilterTruncate($length, $ending = '...', $boundary = false) filtr obcięcie
 * @method self addFilterUppercase() filtr wielkie litery
 * @method self addFilterUrl() filtr url
 * @method self addFilterUrlencode() filtr urlencode
 * @method self addFilterZeroToNull() filtr zero do null'a
 */
class Tree extends \Mmi\Form\Element\ElementAbstract {

	/**
	 * Funkcja użytkownika, jest wykonywana na końcu konstruktora
	 */
	public function init() {
		$this->addFilterEmptyToNull();
		return parent::init();
	}

	/**
	 * Ustawia strukturę drzewka
	 * @param array $structure
	 * @return \Cms\Form\Element\Tree
	 */
	public function setStructure(array $structure) {
		$this->setOption('structure', $structure);
		return $this;
	}

	/**
	 * Ustawia wielokrotny wybór na drzewku
	 * @return \Cms\Form\Element\Tree
	 */
	public function setMultiple($multiple = true) {
		$this->setOption('multiple', $multiple);
		return $this;
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		//powolanie widoku, CSS i JavaScriptow
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headLink()->appendStylesheet($view->baseUrl . '/resource/cmsAdmin/css/tree.css');
		$view->headLink()->appendStylesheet($view->baseUrl . '/resource/cmsAdmin/js/jstree/themes/default/style.min.css');
		$view->headScript()->prependFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/jstree/jstree.min.js');

		//glowny kontener drzewa
		$html = '<div class="tree_container">';
		$html .= $this->_getHtmlTree();
		$this->unsetOption('structure');
		$html .= '<input type="hidden" ' . $this->_getHtmlOptions() . '/></div>';

		return $html;
	}

	/**
	 * Zwraca drzewko danych w postaci html
	 * @return string
	 */
	private function _getHtmlTree() {
		//pobranie struktury
		$structure = $this->getOption('structure');
		//bez struktury zwraca pusty string
		if (!is_array($structure) || empty($structure)) {
			return '';
		}
		//bez dzieci rowniez zwraca pusty string
		if (!isset($structure['children'])) {
			return '';
		}
		//skladam identyfikator galezi
		$treeId = $this->getOption('id') . '_tree';
		//zidentyfikowana gałąź drzewa
		$html = '<div class="tree_structure" id="' . $treeId . '">';
		$html .= $this->_generateTree($structure, '');
		$html .= '</div>';
		$html .= '<input type="button" id="' . $treeId . '_clear" class="tree_clear" value="wyczyść wybór" />';

		$this->_generateJs($treeId);

		return $html;
	}

	/**
	 * Generuje fragmenty drzewka
	 * @param array $node
	 * @param string $html
	 * @return string
	 */
	private function _generateTree($node, $html) {
		//jezeli nie ma wezłów z dzieciakami to zwracam pusty html
		if (!isset($node['children']) || !is_array($node['children']) || count($node['children']) == 0) {
			return $html;
		}
		//zaznaczone wartości
		$values = explode(';', $this->getValue());
		$html .= '<ul>';
		//iteracja po dzieciakach i budowa lisci drzewa
		foreach ($node['children'] as $child) {
			if (isset($child['record'])) {
				$children = isset($child['children']) ? $child['children'] : [];
				$child = $child['record']->toArray();
				$child['children'] = $children;
			}
			$select = 'false';
			if (in_array($child['id'], $values)) {
				$select = 'true';
			}
			$disabled = 'false';
			if (isset($child['allow']) && !$child['allow']) {
				$disabled = 'true';
			}
			$html .= '<li id="' . $child['id'] . '"';
			$html .= ' data-jstree=\'{"type":"default", "disabled":' . $disabled . ', "selected":' . $select . '}\'>' . $child['name'];
			$html = self::_generateTree($child, $html);
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}

	/**
	 * Generuje JS do odpalenia drzewka
	 * @param string $treeId
	 * @return void
	 */
	private function _generateJs($treeId) {
		$id = $this->getOption('id');
		$treeClearId = $treeId . '_clear';
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headScript()->appendScript("$(document).ready(function () {
				$('#$treeId').jstree({
					'core': {
						'themes': {
							'name': 'default',
							'variant': 'small',
							'responsive' : true,
							'stripes' : true
						},
						'multiple': " . ($this->getOption('multiple') ? 'true' : 'false') . ",
						'expand_selected_onload': true,
						'check_callback' : false
					}
				})
				.on('changed.jstree', function (e, data) {
					var selectedStr = '';
					if (0 in data.selected) {
						selectedStr = data.selected[0];
					}
					for (idx = 1, len = data.selected.length; idx < len; ++idx) {
						selectedStr = selectedStr.concat(';' + data.selected[idx]) 
					}
					$('#$id').val(selectedStr);
				});
				$('#$treeClearId').click(function () {
					$('#$id').val('');
					$('#$treeId').jstree('deselect_all');
				});
			});
		");
	}

}
