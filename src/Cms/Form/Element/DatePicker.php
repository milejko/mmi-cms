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
 * Element DateTimePicker
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
class DatePicker extends \Mmi\Form\Element\ElementAbstract {

	/**
	 * Przekazanie widoku + pliki
	 */
	public function __construct($name) {
		parent::__construct($name);
		$this->view = \Mmi\App\FrontController::getInstance()->getView();
		$this->view->headLink()->appendStylesheet($this->view->baseUrl . '/resource/cmsAdmin/css/datetimepicker.css');
		$this->view->headScript()->prependFile($this->view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$this->view->headScript()->appendFile($this->view->baseUrl . '/resource/cmsAdmin/js/jquery/datetimepicker.js');

		$this->dateEvent = '';
	}

	/**
	 * Ustawia datę startową
	 * @param string $date - sformatowany string czasu
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setDateStart($date) {
		return $this->setOption('dateStart', $date);
	}

	/**
	 * Ustawia minimalną datę
	 * @param string $date - sformatowany string czasu
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setDateMin($date) {
		return $this->setOption('dateMin', $date);
	}

	/**
	 * Ustawia maksymanlną datę
	 * @param string $date - sformatowany string czasu
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setDateMax($date) {
		return $this->setOption('dateMax', $date);
	}

	/**
	 * Ustawia pobieranie min zakresu daty z pola
	 * @param string $poleEvent - id pola min
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setLimitMin($poleEvent) {
		$this->dateEvent = ',onChangeDateTime:logic_min, onShow:logic_min';
		$this->view->headScript()->appendScript("
			var logic_min = function( curr ){
				var min = jQuery('#" . $poleEvent . "').datetimepicker('getValue');
				var time = false;

				if( $.datepicker.formatDate('yy-mm-dd', curr) === $.datepicker.formatDate('yy-mm-dd', min) ){
					time = min;
				}

				this.setOptions({
					minDate:jQuery('#" . $poleEvent . "').val()?jQuery('#" . $poleEvent . "').val():false,
					minDateTime:time
				});
			};
		");
	}

	/**
	 * Ustawia pobieranie max zakresu daty z pola
	 * @param string $poleEvent - id pola max
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setLimitMax($poleEvent) {
		$this->dateEvent = ',onChangeDateTime:logic_max, onShow:logic_max';
		$this->view->headScript()->appendScript("
			var logic_max = function( curr ){
				var max = jQuery('#" . $poleEvent . "').datetimepicker('getValue');
				var time = false;

				if( $.datepicker.formatDate('yy-mm-dd', curr) === $.datepicker.formatDate('yy-mm-dd', max) ){
					time = max;
				}

				this.setOptions({
					maxDate:jQuery('#" . $poleEvent . "').val()?jQuery('#" . $poleEvent . "').val():false,
					maxDateTime:time
				});
			};
		");
	}

	/**
	 * Ustawia datę końcową
	 * @param string $date - sformatowany string czasu
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setDateEnd($date) {
		return $this->setOption('dateEnd', $date);
	}

	/**
	 * Ustawia format daty
	 * @param string $format
	 * @return \Mmi\Form\Element\DatePicker
	 */
	public function setFormat($format) {
		return $this->setOption('format', $format);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$format = isset($this->_options['format']) ? $this->_options['format'] : 'Y-m-d';

		$dateStart = isset($this->_options['dateStart']) ? $this->_options['dateStart'] : 'false';
		$dateEnd = isset($this->_options['dateEnd']) ? $this->_options['dateEnd'] : 'false';
		$dateMin = isset($this->_options['dateMin']) ? "'" . $this->_options['dateMin'] . "'" : 'false';
		$dateMax = isset($this->_options['dateMax']) ? "'" . $this->_options['dateMax'] . "'" : 'false';
		$datepicker = isset($this->_options['datepicker']) ? $this->_options['datepicker'] : 'true';
		
		$id = $this->getOption('id');

		$this->view->headScript()->appendScript("$(document).ready(function () {
				$('#$id').datetimepicker({timepicker: false, minDate: $dateMin, maxDate: $dateMax, dateStart: '$dateStart', dateEnd: '$dateEnd', format:'$format', validateOnBlur: true, closeOnDateSelect: true $this->dateEvent});
				$.datetimepicker.setLocale('pl');					
			});
		");

		unset($this->_options['dateStart']);
		unset($this->_options['dateEnd']);
		unset($this->_options['format']);
		$html = '<div class="field"><input id="' . $id . '" class="datePickerField dp-applied" ';
		$html .= 'type="text" ' . $this->_getHtmlOptions() . '/></div>';

		return $html;
	}

}
