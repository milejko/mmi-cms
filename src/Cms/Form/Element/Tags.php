<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

use \Cms\Model\TagRelationModel;

/**
 * Element select
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
 * @method self setMultioptions(array $multioptions = []) ustawia multiopcje
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
class Tags extends \Mmi\Form\Element\Select {

	/**
	 * pole multiselect
	 */
	public function __construct($name) {
		parent::__construct($name);
		$this->setMultiple()
			->setIgnore()
			->setValue([])
			->setMultioptions((new \Cms\Orm\CmsTagQuery)->orderAscId()->findPairs('tag', 'tag'));
	}

	/**
	 * Ustawia objekt cms
	 * @param string $object
	 * @return \Cms\Form\Element\Tags
	 */
	public function setObject($object) {
		return $this->setOption('object', $object);
	}

	/**
	 * Ustawianie tagów na podstawie formularza
	 * @return \Cms\Form\Element\Tags
	 */
	public function setAutoTagValue() {
		//brak rekordu
		if (!$this->_form->hasRecord()) {
			return $this;
		}
		//ustawianie wartości
		$this->setValue((new TagRelationModel($this->getOption('object') ? $this->getOption('object') : $this->_form->getFileObjectName(), $this->_form->getRecord()->getPk()))
				->getTagRelations());
		//zwrot obiektu
		return $this;
	}

	/**
	 * Zapis tagów po zapisie formularza
	 */
	public function onFormSaved() {
		//zapis tagów
		(new TagRelationModel($this->getOption('object') ? $this->getOption('object') : $this->_form->getFileObjectName(), $this->_form->getRecord()->getPk()))
			->createTagRelations($this->getValue());
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$id = $this->getOption('id');
		$inputId = \str_replace('-', '_', $id);
		//ustawianie wartości
		$this->setAutoTagValue();
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headLink()->appendStylesheet($view->baseUrl . '/resource/cmsAdmin/css/chosen.min.css');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/chosen.jquery.min.js');
		$view->headScript()->appendScript("
                    $(document).ready(function ($) {
                        $('#" . $id . "').chosen({			    
			    disable_search_threshold:10,
			    placeholder_text_multiple:'Wpisz lub wybierz tagi',
			    no_results_text:'Tag nieodnaleziony'
                        });
			
			var customTagPrefix = '';

			// event 
			$('#" . $inputId . "_chosen input').keyup(function(event) {

				// wiecej niz 3 znaki, entery
				if (this.value && this.value.length >= 3 && (event.which === 13 || event.which === 188)) {

					// podswietlamy
					var highlighted = $('#" . $inputId . "_chosen').find('li.active-result.highlighted').first();

					if (event.which === 13 && highlighted.text() !== '')
					{
						//sprawdzamy czy juz jest dodany
						var customOptionValue = customTagPrefix + highlighted.text();
						$('#" . $id . " option').filter(function () { return $(this).val() == customOptionValue; }).remove();

						var tagOption = $('#" . $id . " option').filter(function () { return $(this).html() == highlighted.text(); });
						tagOption.attr('selected', 'selected');
					}
					// Add the custom tag option
					else
					{
						var customTag = this.value;

						// test czy juz taki tag istnieje
						var tagOption = $('#" . $id . " option').filter(function () { return $(this).html() == customTag; });
						if (tagOption.text() !== '')
						{
							tagOption.attr('selected', 'selected');
						}
						else
						{
							var option = $('<option>');
							option.text(this.value).val(customTagPrefix + this.value);
							option.attr('selected','selected');

							//dodanie nowego taga
							$('#" . $id . "').append(option);
						}
					}

					this.value = '';
					$('#" . $id . "').trigger('chosen:updated');
					event.preventDefault();

				}
			});
                    });
		");

		$values = is_array($this->getValue()) ? $this->getValue() : [$this->getValue()];

		if ($this->issetOption('multiple')) {
			$this->setName($this->getName() . '[]');
		}

		//nagłówek selecta
		$html = '<select ' . $this->_getHtmlOptions() . '>';
		//generowanie opcji
		foreach ($this->getMultioptions() as $key => $caption) {
			$disabled = '';
			//disabled
			if (strpos($key, ':disabled') !== false && !is_array($caption)) {
				$key = '';
				$disabled = ' disabled="disabled"';
			}
			//dodawanie pojedynczej opcji
			$html .= '<option value="' . $key . '"' . $this->_calculateSelected($key, $values) . $disabled . '>' . $caption . '</option>';
		}
		$html .= '</select>';
		return $html;
	}

	/**
	 * przerobienie tablicy + klucz
	 * @return array
	 */
	public function getValue() {
		$arr = [];
		foreach ($this->_options['value'] as $key) {
			$arr[$key] = $key;
		}
		return $arr;
	}

	/**
	 * łączenie wartości
	 * @return array
	 */
	public function getMultioptions() {
		return array_merge($this->getValue(), parent::getMultioptions());
	}

}
