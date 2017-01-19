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
 * @method self setImprint($imprint) ustawia tablicę z polami metryczki
 * 
 * @method array getImprint() pobiera pola metryczki
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
	 * Ustawia objekt cms_
	 * @param string $object
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setObject($object) {
		return $this->setOption('object', $object);
	}

	/**
	 * Ustawia rozmiar chunka
	 * @param string $size
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setChunkSize($size) {
		return $this->setOption('chunkSize', $size);
	}

	/**
	 * Ustawia maksymalny rozmiar pliku
	 * @param string $size
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setMaxFileSize($size) {
		return $this->setOption('maxFileSize', $size);
	}

	/**
	 * Ustawia maksymalną ilość plików możliwą do wgrania
	 * @param integer $count
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setMaxFileCount($count) {
		return $this->setOption('maxFileCount', intval($count));
	}

	/**
	 * Ustawia, czy pokazać konsolę z komunikatami
	 * @param boolean $show
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setShowConsole($show = true) {
		return $this->setOption('showConsole', boolval($show));
	}

	/**
	 * Dodaje dozwolony typ pliku
	 * @param string $mime typ pliku mime, np. image/jpeg
	 * @param string $extensions lista rozszerzeń po przecinku, np. jpg,jpeg
	 * @param string $title opis, np. Obrazki Jpg
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addAllowedType($mime, $extensions, $title = '') {
		$types = $this->getOption('mimeTypes');
		//brak typów - pusta lista
		if (null === $types) {
			$types = [];
		}
		if (empty($title)) {
			$title = $extensions;
		}
		$types[] = ['title' => $title, 'extensions' => $extensions, 'mime' => $mime];
		return $this->setOption('mimeTypes', $types);
	}

	/**
	 * Dodaje dozwolony typ pliku JPG
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addAllowedJpg() {
		return $this->addAllowedType('image/jpeg', 'jpg,jpeg,jpe');
	}

	/**
	 * Dodaje dozwolony typ pliku PNG
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addAllowedPng() {
		return $this->addAllowedType('image/png', 'png');
	}

	/**
	 * Dodaje dozwolony typ pliku GIF
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addAllowedGif() {
		return $this->addAllowedType('image/gif', 'gif');
	}

	/**
	 * Ustawia, że z aktualnej listy plików wyświetla tylko obrazki
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setTypeImages() {
		return $this->setOption('fileTypes', 'images');
	}

	/**
	 * Ustawia, że z aktualnej listy plików wyświetla wszystkie poza obrazkami
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setTypeNotImages() {
		return $this->setOption('fileTypes', 'notImages');
	}

	/**
	 * Ustawia akcję wykonywaną po przesłaniu całego pliku i zapisaniu rekordu
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setAfterUploadAction($module, $controller, $action) {
		return $this->setOption('afterUpload', ['module' => $module, 'controller' => $controller, 'action' => $action]);
	}

	/**
	 * Ustawia akcję wykonywaną po usunięciu pliku
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setAfterDeleteAction($module, $controller, $action) {
		return $this->setOption('afterDelete', ['module' => $module, 'controller' => $controller, 'action' => $action]);
	}

	/**
	 * Ustawia akcję wykonywaną po edycji opisu pliku
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @return \Cms\Form\Element\Plupload
	 */
	public function setAfterEditAction($module, $controller, $action) {
		return $this->setOption('afterEdit', ['module' => $module, 'controller' => $controller, 'action' => $action]);
	}

	/**
	 * Dodaje pole do metryczki
	 * @param string $type typ pola: text, checkbox, textarea, tinymce, select
	 * @param string $name nazwa pola
	 * @param string $label labelka pola
	 * @param string $options opcje pola
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addImprintElement($type, $name, $label = null, $options = []) {
		$imprint = $this->getOption('imprint');
		//brak pól - pusta lista
		if (null === $imprint) {
			$imprint = [];
		}
		$imprint[] = ['type' => $type, 'name' => $name, 'label' => ($label ?: $name), 'options' => $options];
		return $this->setOption('imprint', $imprint);
	}

	/**
	 * Dodaje pole tekstowe do metryczki
	 * @param string $name nazwa pola
	 * @param string $label labelka pola
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addImprintElementText($name, $label) {
		return $this->addAllowedType('text', $name, $label);
	}

	/**
	 * Dodaje pole textarea do metryczki
	 * @param string $name nazwa pola
	 * @param string $label labelka pola
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addImprintElementTextarea($name, $label) {
		return $this->addAllowedType('textarea', $name, $label);
	}

	/**
	 * Dodaje pole edytora wysiwyg do metryczki
	 * @param string $name nazwa pola
	 * @param string $label labelka pola
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addImprintElementTinymce($name, $label) {
		return $this->addAllowedType('tinymce', $name, $label);
	}

	/**
	 * Dodaje pole checkbox do metryczki
	 * @param string $name nazwa pola
	 * @param string $label labelka pola
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addImprintElementCheckbox($name, $label) {
		return $this->addAllowedType('checkbox', $name, $label);
	}

	/**
	 * Dodaje pole listy do metryczki
	 * @param string $name nazwa pola
	 * @param string $label labelka pola
	 * @param array $option opcje
	 * @return \Cms\Form\Element\Plupload
	 */
	public function addImprintElementSelect($name, $label, $option) {
		return $this->addAllowedType('select', $name, $label, $option);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$view->headLink()->appendStyleSheet($view->baseUrl . '/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.css');
		$view->headLink()->appendStyleSheet($view->baseUrl . '/resource/cmsAdmin/js/jquery-ui/jquery-ui.structure.min.css');
		$view->headLink()->appendStyleSheet($view->baseUrl . '/resource/cmsAdmin/js/jquery-ui/jquery-ui.theme.min.css');
		$view->headLink()->appendStyleSheet($view->baseUrl . '/resource/cmsAdmin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css');
		$view->headLink()->appendStyleSheet($view->baseUrl . '/resource/cmsAdmin/js/plupload/plupload.conf.css');
		$view->headScript()->prependFile($view->baseUrl . '/resource/cmsAdmin/js/jquery/jquery.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/tiny/tinymce.min.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/plupload.full.min.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/i18n/pl.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js');
		$view->headScript()->appendFile($view->baseUrl . '/resource/cmsAdmin/js/plupload/plupload.conf.js');

		$id = $this->getOption('id');
		$object = 'library';
		$objectId = null;
		if ($this->_form->hasRecord()) {
			$object = $this->_form->getFileObjectName();
			$objectId = $this->_form->getRecord()->getPk();
		}
		//jeśli wymuszony inny object
		if ($this->getOption('object')) {
			$object = $this->getOption('object');
		}
		if (!$objectId) {
			$object = 'tmp-' . $object;
			$objectId = \Mmi\Session\Session::getNumericId();
		}
		if (!$this->_form->hasRecord()) {
			$objectId = null;
		}

		//dołączanie skryptu
		$view->headScript()->appendScript("
			$(document).ready(function () {
				'use strict';
				var conf = $.extend(true, {}, PLUPLOADCONF.settings);
				conf.form_element_id = '$id';
				conf.form_object = '$object';
				conf.form_object_id = '$objectId';
				" . ($this->getOption('showConsole') ? "conf.log_element = '" . $id . "-console';" : "") . "
				" . ($this->getOption('chunkSize') ? "conf.chunk_size = '" . $this->getOption('chunkSize') . "';" : "") . "
				" . ($this->getOption('maxFileSize') ? "conf.max_file_size = '" . $this->getOption('maxFileSize') . "';" : "") . "
				" . ($this->getOption('maxFileCount') ? "conf.max_file_cnt = " . $this->getOption('maxFileCount') . ";" : "") . "
				" . ($this->getOption('mimeTypes') ? "conf.filters.mime_types = " . json_encode($this->getOption('mimeTypes')) . ";" : "") . "
				" . ($this->getOption('fileTypes') ? "conf.file_types = '" . $this->getOption('fileTypes') . "';" : "") . "
				" . ($this->getOption('afterUpload') ? "conf.after_upload = " . json_encode($this->getOption('afterUpload')) . ";" : "") . "
				" . ($this->getOption('afterDelete') ? "conf.after_delete = " . json_encode($this->getOption('afterDelete')) . ";" : "") . "
				" . ($this->getOption('afterEdit') ? "conf.after_edit = " . json_encode($this->getOption('afterEdit')) . ";" : "") . "
				$('#$id').plupload(conf);
			});
		");

		$html = '<div id="' . $id . '">';
		$html .= '<p>Twoja przeglądarka nie posiada wsparcia dla HTML5.</p>';
		$html .= '<p>Proszę zaktualizować oprogramowanie.</p>';
		$html .= '</div>';
		$html .= '<div id="' . $id . '-confirm" class="plupload-confirm-container" title="">';
		$html .= '<p><span class="dialog-info"></span><span class="dialog-file"></span><span class="dialog-info-2"></span></p>';
		$html .= '</div>';
		$html .= '<div id="' . $id . '-edit" class="plupload-edit-container" title="">';
		$html .= '<fieldset>';
		$html .= $this->_renderImprintElements();
		$html .= '<div id="' . $id . '-edit-buttons" class="plupload-edit-buttons">';
		$html .= '<input type="checkbox" name="active" id="' . $id . '-edit-active" value="1"><label for="' . $id . '-edit-active">aktywny</label>';
		$html .= '<input type="checkbox" name="sticky" id="' . $id . '-edit-sticky" value="1"><label for="' . $id . '-edit-sticky">wyróżniony</label>';
		$html .= '</div>';
		$html .= '</fieldset>';
		$html .= '<div class="dialog-error"><p></p><span class="ui-icon ui-icon-alert"></span></div>';
		$html .= '</div>';
		if ($this->getOption('showConsole')) {
			$html .= '<div class="plupload-log-container">';
			$html .= '<pre class="plupload-log-console" id="' . $id . '-console"></pre>';
			$html .= '</div>';
		}
		return $html;
	}

	/**
	 * Rendering elementów metryczki
	 * @return string
	 */
	protected function _renderImprintElements() {
		//pusta metryczka
		if (!is_array($this->getImprint())) {
			return;
		}
		$html = '';
		//iteracja po elementach
		foreach ($this->getImprint() as $element) {
			$html .= $this->_renderImprintElement($element);
		}
		return $html;
	}

	/**
	 * Rendering elementu formularza
	 * @param array
	 * @return string
	 */
	protected function _renderImprintElement($element) {
		//walidacja
		if (!isset($element['type']) || !isset($element['name']) || !isset($element['label'])) {
			return;
		}
		//identyfikator pola
		$fieldId = $this->getId() . '-' . (new \Mmi\Filter\Url)->filter($element['name']);
		//element label
		$label = '<label for="' . $fieldId . '">' . $element['label'] . (($element['type'] != 'checkbox') ? ':' : '') . '</label>';
		//input text
		if ($element['type'] == 'text') {
			return $label .
					'<input id="' . $fieldId . '" type="' . $element['type'] . '" name="' . $element['name'] . '" class="imprint ' . $element['type'] . '">';
		}
		//input checkbox
		if ($element['type'] == 'checkbox') {
			return '<input id="' . $fieldId . '" type="' . $element['type'] . '" name="' . $element['name'] . '" value="1" class="imprint ' . $element['type'] . '">' . $label;
		}
		//textarea
		if ($element['type'] == 'textarea') {
			return $label .
					'<textarea id="' . $fieldId . '" name="' . $element['name'] . '" class="imprint ' . $element['type'] . '"></textarea>';
		}
		//tinymce
		if ($element['type'] == 'tinymce') {
			return $label .
					'<textarea id="' . $fieldId . '" name="' . $element['name'] . '" class="plupload-edit-tinymce imprint ' . $element['type'] . '"></textarea>';
		}
		//lista
		if ($element['type'] == 'select') {

			$option = [];
			foreach ($element['options'] as $key => $value) {
				if (is_array($value)) {
					$data = '';
					foreach ($value['data'] as $data_key => $data_val) {
						$data .= $data_key . '="' . $data_val . '"';
					}
					array_push($option, '<option value="' . $key . '" ' . $data . '>' . $value['value'] . '</option>"');
				} else {
					array_push($option, '<option value="' . $key . '">' . $value . '</option>"');
				}
			}
			
			//dodaje podglad obrazka
			$image = '';
			if( isset($element['preview']) ){
				$image = "
				<div class='col_3' id='image_$fieldId'></div>
				<script type='text/javascript'>
				// <![CDATA[
					$(document).ready(function () {
						'use strict';
						$('#$fieldId').change(function() {
							$('#image_$fieldId').html( '<img src=\"' + $(this).find(':selected').attr('data-image-url') +'\"/>' );
						});
					});
				// ]]>
				</script>
				<div class='col_12'></div><br clear='both'/>
				";
			}

			return $label .
					'<select id="' . $fieldId . '" name="' . $element['name'] . '" class="' .($image!=''?'col_9':''). ' plupload-edit-tinymce imprint ' . $element['type'] . '">' . implode($option) . '</select>' . $image;
		}
	}

}
