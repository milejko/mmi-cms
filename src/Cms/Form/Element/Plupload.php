<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element plupload
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
class Plupload extends \Mmi\Form\Element\ElementAbstract {
	
	/**
	 * Ustawia rozmiar chunka
	 * @param string $size
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setChunkSize($size) {
		return $this->setOption('chunkSize', $size);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headLink()->appendStyleSheet($view->baseUrl . '/resource/cmsAdmin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css');
		$view->headScript()->prependFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/plupload.full.min.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/i18n/pl.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/plupload.conf.js');
		
		$class = $this->getOption('id');
		$this->setOption('class', trim($this->getOption('class') . ' ' . $class));
		$object = '';
		$objectId = '';
		//odczyt zmiennych z rekordu
		if ($this->_form->hasRecord()) {
			$object = $this->_form->getFileObjectName();
			$objectId = $this->_form->getRecord()->getPk();
		}
		$t = round(microtime(true));
		$hash = md5(\Mmi\Session\Session::getId() . '+' . $t . '+' . $objectId);
		$id = $this->getOption('id');
		
		//dołączanie skryptu
		$view->headScript()->appendScript("
			$(document).ready(function () {
				'use strict';
				var conf = $.extend({}, PLUPLOADCONF.settings);
				conf.log_element = '" . $id . "-console';
				//modyfikacja konfiguracji
				$('#$id').plupload(conf);
			});
		");

		$html = '<div id="' . $id . '">';
		$html .= '<p>Twoja przeglądarka nie posiada wsparcia dla HTML5.</p>';
		$html .= '<p>Proszę zaktualizować oprogramowanie.</p>';
		$html .= '</div>';
		$html .= '<div class="plupload-log-container">';
		$html .= '<pre class="plupload-log-console" id="' . $id . '-console"></pre>';
		$html .= '</div>';
		return $html;
	}

}
