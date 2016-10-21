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
 * @method self setCss($mixed) ustawia ścieżki do CSS ze stylami kontentu edytora
 * @method self setTheme($theme) ustawia motyw
 * @method self setSkin($skin) ustawia skórkę
 * @method self setPlugins($mixed) ustawia włączone pluginy
 * @method self setContextMenu($menu) ustawia opcje dostępne w menu kontekstowym
 * @method self setResize($resize) ustawia możliwość zmiany rozmiarów
 * @method self setMenubar($menubar) ustawia możliwość wł./wył. menu górnego
 * @method self setImageAdvanceTab($advTab) ustawia możliwość wł./wył. zakł. zaawansowane dla obrazów
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
	 * Ustawia tryb własny
	 * @param string $mode własna konfiguracja
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setMode($mode) {
		return $this->setOption('mode', $mode);
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
		return $this->setOption('height', intval($height));
	}

	/**
	 * Ustawia dodatkowe parametry do konfiguracji - RAW zgodne z dokumentacją TinyMce
	 * klucz_tiny1: wartosc1, klucz_tiny2: wartosc2
	 * @param string $custom
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function setCustomConfig($custom) {
		return $this->setOption('customConfig', $custom);
	}
	
	//Pola do konfiguracji edytora, żeby można było customizować
	/**
	 * Paski narzędziowe
	 * @var string
	 */
	protected $_toolbars;
	/**
	 * Specyficzne ustawienia dla danego trybu
	 * @var string
	 */
	protected $_other;
	/**
	 * Wspóle ustawienia dla wszystkich trybów
	 * @var string
	 */
	protected $_common;
	/**
	 * Kroje i rozmiary czcionek
	 * @var string
	 */
	protected $_font;

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/tiny/tinymce.min.js');
		
		//bazowa wspólna konfiguracja
		$this->_baseConfig();
		//tryb edytora
		$mode = $this->getMode() ? $this->getMode() : 'default';
		//metoda konfiguracji edytora
		$modeConfigurator = '_mode' . ucfirst($mode);
		if (method_exists($this, $modeConfigurator)) {
			$this->$modeConfigurator();
		}
		
		$class = $this->getOption('id');
		$this->setOption('class', trim($this->getOption('class') . ' ' . $class));
		$object = '';
		$objectId = '';
		//odczyt zmiennych z rekordu
		if ($this->_form->hasRecord()) {
			$object = $this->_form->getFileObjectName();
			$objectId = $this->_form->getRecord()->getPk();
		}
		if (!$objectId) {
			$object = 'tmp-' . $object;
			$objectId = \Mmi\Session\Session::getNumericId();
		}
		$t = round(microtime(true));
		$hash = md5(\Mmi\Session\Session::getId() . '+' . $t . '+' . $objectId);
		//dołączanie skryptu
		$view->headScript()->appendScript("
			tinyMCE.init({
				selector: '." . $class . "',
				language: 'pl',
				" . $this->_renderConfig('theme', 'theme', 'modern') . "
				" . $this->_renderConfig('skin', 'skin', 'lightgray') . "
				" . $this->_renderConfig('plugins', 'plugins') . "
				" . $this->_toolbars . "
				" . $this->_renderConfig('contextmenu', 'contextMenu') . "
				" . $this->_renderConfig('width', 'width', '') . "
				" . $this->_renderConfig('height', 'height', 320) . "
				" . $this->_renderConfig('resize', 'resize', true) . "
				" . $this->_renderConfig('menubar', 'menubar', true) . "
				" . $this->_renderConfig('image_advtab', 'imageAdvanceTab', true) . "
				" . $this->_font . "
				" . $this->_renderConfig('content_css', 'css') . "
				" . ($this->getCustomConfig() ? trim($this->getCustomConfig(), ",") . "," : "") . "
				" . $this->_other . "
				" . $this->_common . "
                hash: '$hash',
                object: '$object',
                objectId: '$objectId',
                time: '$t',
                baseUrl: request.baseUrl,
				image_list: request.baseUrl + '/?module=cms&controller=file&action=list&object=$object&objectId=$objectId&t=$t&hash=$hash'
			});
		");
		
		//unsety zbędnych opcji
		$this->unsetMode()->unsetCustomConfig()->unsetCss()->unsetTheme()->unsetSkin()
			->unsetPlugins()->unsetContextMenu()->unsetResize()->unsetMenubar()
			->unsetImageAdvanceTab();

		return parent::fetchField();
	}
	
	/**
	 * Renderuje opcję konfiguracji TinyMce na podstawie opcji pola formularza
	 * @param string $tinyKey klucz konfiguracji edytora TinyMce
	 * @param string $optionKey klucz opcji formularza
	 * @param mixed $defaultVal wartość domyślna
	 * @return string
	 */
	protected function _renderConfig($tinyKey, $optionKey, $defaultVal = null) {
		if (null === $optionVal = $this->getOption($optionKey)) {
			if ($defaultVal === null) {
				return "";
			}
			$optionVal = $defaultVal;
		}
		$tinyKey .= ": ";
		if (is_array($optionVal)) {
			$tinyKey .= "['" . implode("', '", $optionVal) . "']";
		} elseif (is_string($optionVal)) {
			$tinyKey .= "'" . $optionVal . "'";
		} elseif (is_bool($optionVal)) {
			$tinyKey .= ($optionVal) ? "true" : "false";
		} elseif (is_int($optionVal) || is_float($optionVal)) {
			$tinyKey .= $optionVal;
		} elseif (is_object($optionVal)) {
			$tinyKey .= json_encode($optionVal);
		} else {
			return "";
		}
		return trim($tinyKey, ",") . ",";
	}
	
	/**
	 * Bazowa konfiguracja dla wszystkich edytorów
	 */
	protected function _baseConfig() {
		if ($this->getPlugins() === null) {
			$this->setPlugins([
				'lioniteimages,advlist,anchor,autolink,autoresize,charmap,code,contextmenu,fullscreen',
				'hr,image,insertdatetime,link,lists,media,nonbreaking,noneditable,paste,print,preview',
				'searchreplace,tabfocus,table,textcolor,visualblocks,visualchars,wordcount'
			]);
		}
		$this->_common = "
			autoresize_min_height: " . ($this->getHeight()? $this->getHeight() : 300) . ",
			document_base_url: request.baseUrl,
			convert_urls: false,
			entity_encoding: 'raw',
			relative_urls: false,
			paste_data_images: false,
			plugin_preview_height: 700,
			plugin_preview_width: 1100,
		";
		$this->_font = "
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
				'Wingdings=wingdings,zapf dingbats',
			fontsize_formats: '1px 2px 3px 4px 6px 8px 9pc 10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 36px 48px 50px 72px 100px',
		";
	}
	
	/**
	 * Konfiguracja dla trybu Simple
	 */
	protected function _modeSimple() {
		$this->_toolbars = "
			toolbar1: 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify',
		";
		if ($this->getContextMenu() === null) {
			$this->setContextMenu('link image inserttable | cell row column deletetable');
		}
		if ($this->getResize() === null) {
			$this->setResize(false);
		}
		if ($this->getMenubar() === null) {
			$this->setMenubar(false);
		}
	}
	
	/**
	 * Konfiguracja dla trybu Advanced
	 */
	protected function _modeAdvanced() {
		$this->_toolbars = "
			toolbar1: 'undo redo | cut copy paste pastetext | searchreplace | bold italic underline strikethrough | subscript superscript | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect | forecolor backcolor',
			toolbar2: 'styleselect | table | bullist numlist outdent indent blockquote | link unlink anchor | image media lioniteimages | preview fullscreen code | charmap visualchars nonbreaking inserttime hr',
		";
		if ($this->getContextMenu() === null) {
			$this->setContextMenu('link image media inserttable | cell row column deletetable');
		}
	}
	
	/**
	 * Konfiguracja dla trybu Default
	 */
	protected function _modeDefault() {
		$this->_toolbars = "
			toolbar1: 'undo redo | bold italic underline strikethrough | forecolor backcolor | styleselect | bullist numlist outdent indent | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | link unlink anchor | image media lioniteimages | preview',
		";
		if ($this->getContextMenu() === null) {
			$this->setContextMenu('link image media inserttable | cell row column deletetable');
		}
	}

}
