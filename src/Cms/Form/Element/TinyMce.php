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
 * Element tinymce
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
class TinyMce extends \Mmi\Form\Element\Textarea {
	
	/**
	 * Ustawia tryb zaawansowany
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setModeAdvanced() {
		return $this->setOption('mode', 'advanced');
	}

	/**
	 * Ustawia tryb domyślny
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setModeDefault() {
		return $this->setOption('mode', null);
	}

	/**
	 * Ustawia tryb prosty
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setModeSimple() {
		return $this->setOption('mode', 'simple');
	}

	/**
	 * Ustawia szerokość w px
	 * @param int $width
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setWidth($width) {
		return $this->setOption('width', intval($width));
	}

	/**
	 * Ustawia wysokość w px
	 * @param int $height
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setHeight($height) {
		return $this->setOption('heigth', intval($height));
	}

	/**
	 * Ustawia parametr oninit
	 * @param string $oninit
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setOnInit($oninit) {
		return $this->setOption('oninit', $oninit);
	}
	
	/**
	 * Ustawia główny blok treści jako pusty
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setEmptyRootBlock() {
		return $this->setOption('emptyRootBlock', true);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/tiny/tinymce.min.js');

		switch (isset($this->_options['mode']) ? $this->_options['mode'] : null) {
			case 'simple':
				$toolbarOptions = "
					toolbar1 : 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify',
				";
				$plugins = "plugins : 'advlist,anchor,autolink,autoresize,charmap,code,contextmenu,fullscreen,hr,image,insertdatetime,link,lists,media,nonbreaking,noneditable,paste,print,preview,searchreplace,tabfocus,table,template,textcolor,visualblocks,visualchars,wordcount',";
				$theme = "theme : 'modern',";
				$tskin = "skin : 'lightgray',";
				$themeOptions = "image_advtab: true,
				contextmenu: 'link image inserttable | cell row column deletetable',
				width: '" . (isset($this->_options['width']) ? $this->_options['width'] : 400) . "',
				height: '" . (isset($this->_options['height']) ? $this->_options['height'] : 200) . "',
				menubar: false,
				resize : false,
				";
				break;
			case 'advanced':
				$toolbarOptions = "
					toolbar1 : 'undo redo | cut copy paste pastetext | bold italic underline strikethrough | subscript superscript | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect | forecolor backcolor',
					toolbar2 : 'styleselect | table | bullist numlist outdent indent blockquote | link unlink anchor image media code |  preview fullscreen | charmap visualchars nonbreaking inserttime hr template | searchreplace',
				";
				$plugins = "plugins : 'advlist,anchor,autolink,autoresize,charmap,code,contextmenu,fullscreen,hr,image,insertdatetime,link,lists,media,nonbreaking,noneditable,paste,print,preview,searchreplace,tabfocus,table,template,textcolor,visualblocks,visualchars,wordcount',";
				$theme = "theme : 'modern',";
				$tskin = "skin : 'lightgray',";
				$themeOptions = "image_advtab: true,
				contextmenu: 'link image inserttable | cell row column deletetable',
				width: '" . (isset($this->_options['width']) ? $this->_options['width'] : 400) . "',
				height: '" . (isset($this->_options['height']) ? $this->_options['height'] : 200) . "',
				resize : true,
				";
				break;
			default:
				$toolbarOptions = "
					toolbar1 : 'undo redo | bold italic underline strikethrough | forecolor backcolor | styleselect | bullist numlist outdent indent | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | link unlink anchor image insertfile preview',
				";
				$plugins = "plugins : 'advlist,anchor,autolink,autoresize,charmap,code,contextmenu,fullscreen,hr,image,insertdatetime,link,lists,media,nonbreaking,noneditable,paste,print,preview,searchreplace,tabfocus,table,template,textcolor,visualblocks,visualchars,wordcount',";
				$theme = "theme : 'modern',";
				$tskin = "skin : 'lightgray',";
				$themeOptions = "image_advtab: true,
				contextmenu: 'link image inserttable | cell row column deletetable',
				width: '" . (isset($this->_options['width']) ? $this->_options['width'] : '') . "',
				height: '" . (isset($this->_options['height']) ? $this->_options['height'] : 320) . "',
				";
		}
		$this->unsetOption('mode');
		$class = $this->getOption('id');
		$this->setOption('class', trim($this->getOption('class') . ' ' . $class));
		$object = '';
		$objectId = '';
		/** opcjonalna funkcja wywoływana po załadowaniu edytorów */
		$onInit = "";
		if (isset($this->_options['oninit']) && $this->_options['oninit']) {
			$onInit = "oninit : '" . $this->_options['oninit'] . "',";
		}
		$emptyRootBlock = "";
		if ($this->getOption('emptyRootBlock')) {
			$emptyRootBlock = "forced_root_block : '',force_br_newlines : true,force_p_newlines : false,";
		}
		//odczyt zmiennych z rekordu
		if ($this->_form->hasRecord()) {
			$object = $this->_form->getFileObjectName();
			$objectId = $this->_form->getRecord()->getPk();
		}
		$t = round(microtime(true));
		$hash = md5(\Mmi\Session\Session::getId() . '+' . $t . '+' . $objectId);
		//dołączanie skryptu
		$view->headScript()->appendScript("
			tinyMCE.init({
				selector : '." . $class . "',
				language : 'pl',
				" . $theme . "
				" . $tskin . "
				" . $plugins . "
				" . $toolbarOptions . "
				" . $themeOptions . "
				" . $onInit . "
				" . $emptyRootBlock . "
				autoresize_min_height: 300,
				image_list: request.baseUrl + '/cms/file/list?object=$object&objectId=$objectId&t=$t&hash=$hash',
				document_base_url: request.baseUrl,
				convert_urls: false,
				entity_encoding: 'raw',
				relative_urls: false,
				paste_data_images: false,
				font_formats: 'Andale Mono=andale mono,times;'+
					'Arial=arial,helvetica,sans-serif;'+
					'Arial Black=arial black,avant garde;'+
					'Book Antiqua=book antiqua,palatino;'+
					'Comic Sans MS=comic sans ms,sans-serif;'+
					'Courier New=courier new,courier;'+
					'Georgia=georgia,palatino;'+
					'Helvetica=helvetica;'+
					'Impact=impact,chicago;'+
					'Symbol=symbol;'+
					'Tahoma=tahoma,arial,helvetica,sans-serif;'+
					'Terminal=terminal,monaco;'+
					'Times New Roman=times new roman,times;'+
					'Trebuchet MS=trebuchet ms,geneva;'+
					'Verdana=verdana,geneva;'+
					'Webdings=webdings;'+
					'Wingdings=wingdings,zapf dingbats;'+
					'EmpikBTT=EmpikBold;'+
					'EmpikLTT=EmpikLight;'+
					'EmpikRTT=EmpikRegular',
				fontsize_formats: '1px 2px 3px 4px 6px 8px 9pc 10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 36px 48px 50px 72px 100px'
			});
		");

		return parent::fetchField();
	}

}
