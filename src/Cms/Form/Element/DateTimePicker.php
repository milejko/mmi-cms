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
 * @method self setDateMin($dateMin) ustawia datę minimalną
 * @method self setDateMax($dateMax) ustawia datę maksymalną
 * @method self setFormat($format) ustawia format daty np. Y-m-d H:i
 * @method self setDatepicker($datepicker) ustawia włączone wybieranie daty
 * @method self setTimepicker($timepicker) ustawia włączone wybieranie godziny
 * @method self setDateMinField(\Cms\Form\Element\DateTimePicker $field) ustawia pole limitujące datę od dołu
 * @method self setDateMaxField(\Cms\Form\Element\DateTimePicker $field) ustawia pole limitujące datę od góry
 * 
 * Gettery
 * @method string getFormat() pobiera format
 * @method string getDateMin() pobiera datę minimalną
 * @method string getDateMax() pobiera datę maksymalną
 * @method boolean getDatepicker() pobiera możliwość wybrania daty
 * @method boolean getTimepicker() pobiera możliwość wybrania godziny
 * @method \Cms\Form\Element\DateTimePicker getDateMinField() pobiera pole limitujące datę od dołu
 * @method \Cms\Form\Element\DateTimePicker getDateMaxField() pobiera pole limitujące datę od góry
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
class DateTimePicker extends \Mmi\Form\Element\ElementAbstract {

	/**
	 * Przekazanie widoku + pliki
	 */
	public function __construct($name) {
		parent::__construct($name);
		$this->view = \Mmi\App\FrontController::getInstance()->getView();
		$this->view->headLink()->appendStylesheet($this->view->baseUrl . '/resource/cmsAdmin/css/datetimepicker.css');
		$this->view->headScript()->prependFile($this->view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$this->view->headScript()->appendFile($this->view->baseUrl . '/resource/cmsAdmin/js/jquery/datetimepicker.js');
		$this->addFilterEmptyToNull()
			->setDatepicker(true)
			->setTimepicker(true)
			->setFormat('Y-m-d H:i');
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$dateMin = $this->getDateMin() ? "'" . $this->getDateMin() . "'" : 'false';
		$dateMax = $this->getDateMax() ? "'" . $this->getDateMax() . "'" : 'false';
		$datepicker = $this->getDatepicker() ? 'true' : 'false';
		$timepicker = $this->getTimepicker() ? 'true' : 'false';
		$minFieldId = $this->getDateMinField() ? $this->getDateMinField()->getId() : null;
		$maxFieldId = $this->getDateMaxField() ? $this->getDateMaxField()->getId() : null;

		$this->view->headScript()->appendScript("$(document).ready(function () {
				$('#" . $this->getId() . "').datetimepicker({
					allowBlank: true, step: 15, minDate: $dateMin, maxDate: $dateMax,
					datepicker: $datepicker, timepicker: $timepicker, format: '" . $this->getFormat() . "', validateOnBlur: true, 
					onShow: function(currentTime, input) {
						if ('" . $minFieldId . "' != '' && jQuery('#" . $minFieldId . "').val()) {
							this.setOptions({
								minDate: jQuery('#" . $minFieldId . "').val()
							});
							input.attr('data-min-date', jQuery('#" . $minFieldId . "').val());
						}
						if ('" . $maxFieldId . "' != '' && jQuery('#" . $maxFieldId . "').val()) {
							this.setOptions({
								maxDate: jQuery('#" . $maxFieldId . "').val()
							});
							input.attr('data-max-date', jQuery('#" . $maxFieldId . "').val());
						}
					},
					onClose: function(currentTime, input) {
						if (input.attr('data-min-date') != '' && input.val() < input.attr('data-min-date')) {
							input.val('');
						}
						if (input.attr('data-max-date') != '' && input.val() > input.attr('data-max-date')) {
							input.val('');
						}
					}
					});
				$.datetimepicker.setLocale('pl');
			});
		");

		unset($this->_options['dateMin']);
		unset($this->_options['dateMax']);
		unset($this->_options['format']);
		unset($this->_options['datepicker']);
		unset($this->_options['timepicker']);

		return '<input class="datePickerField" data-min-date="' . trim($dateMin, "'") . '" data-max-date="' . trim($dateMax, "'") . '" type="datetime" ' . $this->_getHtmlOptions() . '/>';
	}

}
