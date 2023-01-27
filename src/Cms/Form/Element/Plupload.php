<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

use Mmi\Filter\Url;

/**
 * Element Plupload
 * Gettery
 * @method string getObject() pobiera obiekt
 * @method int getObjectId() pobiera identyfikator obiektu
 * @method int getUploaderId() pobiera identyfikator uploadera
 *
 * Settery
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($id) ustawia identyfikator obiektu
 * @method self setUploaderId($id) ustawia id uploadera
 */
class Plupload extends UploaderElementAbstract
{
    //szablon początku pola
    public const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    public const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    public const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    public const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    public const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    /**
     * Ustawia rozmiar chunka
     * @param string $size
     * @return self
     */
    public function setChunkSize($size)
    {
        return $this->setOption('chunkSize', $size);
    }

    /**
     * Ustawia maksymalny rozmiar pliku
     * @param string $size
     * @return self
     */
    public function setMaxFileSize($size)
    {
        return $this->setOption('maxFileSize', $size);
    }

    /**
     * Ustawia maksymalną ilość plików możliwą do wgrania
     * @param integer $count
     * @return self
     */
    public function setMaxFileCount($count)
    {
        return $this->setOption('maxFileCount', intval($count));
    }

    /**
     * Ustawia, czy pokazać konsolę z komunikatami
     * @param boolean $show
     * @return self
     */
    public function setShowConsole($show = true)
    {
        return $this->setOption('showConsole', boolval($show));
    }

    /**
     * Dodaje dozwolony typ pliku
     * @param string $mime typ pliku mime, np. image/jpeg
     * @param string $extensions lista rozszerzeń po przecinku, np. jpg,jpeg
     * @param string $title opis, np. Obrazki Jpg
     * @return self
     */
    public function addAllowedType($mime, $extensions, $title = '')
    {
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
     * @return self
     */
    public function addAllowedJpg()
    {
        return $this->addAllowedType('image/jpeg', 'jpg,jpeg,jpe');
    }

    /**
     * Dodaje dozwolony typ pliku PNG
     * @return self
     */
    public function addAllowedPng()
    {
        return $this->addAllowedType('image/png', 'png');
    }

    /**
     * Dodaje dozwolony typ pliku GIF
     * @return self
     */
    public function addAllowedGif()
    {
        return $this->addAllowedType('image/gif', 'gif');
    }

    /**
     * Ustawia, że z aktualnej listy plików wyświetla tylko obrazki
     * @return self
     */
    public function setTypeImages()
    {
        return $this->setOption('fileTypes', 'images');
    }

    /**
     * Ustawia, że z aktualnej listy plików wyświetla wszystkie poza obrazkami
     * @return self
     */
    public function setTypeNotImages()
    {
        return $this->setOption('fileTypes', 'notImages');
    }

    /**
     * Ustawia akcję wykonywaną po przesłaniu całego pliku i zapisaniu rekordu
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return self
     */
    public function setAfterUploadAction($module, $controller, $action)
    {
        return $this->setOption('afterUpload', ['module' => $module, 'controller' => $controller, 'action' => $action]);
    }

    /**
     * Ustawia akcję wykonywaną po usunięciu pliku
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return self
     */
    public function setAfterDeleteAction($module, $controller, $action)
    {
        return $this->setOption('afterDelete', ['module' => $module, 'controller' => $controller, 'action' => $action]);
    }

    /**
     * Ustawia akcję wykonywaną po edycji opisu pliku
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return self
     */
    public function setAfterEditAction($module, $controller, $action)
    {
        return $this->setOption('afterEdit', ['module' => $module, 'controller' => $controller, 'action' => $action]);
    }

    /**
     * Dodaje pole do metryczki
     * @param string $type typ pola: text, checkbox, textarea, tinymce, select
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @param string $options opcje pola
     * @return self
     */
    public function addImprintElement($type, $name, $label = null, $options = [])
    {
        $imprint = $this->getOption('imprint');
        //brak pól - pusta lista
        if (null === $imprint) {
            $imprint = [];
        }
        $imprint[] = ['type' => $type, 'name' => $name, 'label' => ($label ? $this->view->_($label) : $name), 'options' => $options];
        return $this->setOption('imprint', $imprint);
    }

    /**
     * Dodaje pole tekstowe do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @return self
     */
    public function addImprintElementText($name, $label)
    {
        return $this->addImprintElement('text', $name, $label);
    }

    /**
     * Dodaje pole textarea do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @return self
     */
    public function addImprintElementTextarea($name, $label)
    {
        return $this->addImprintElement('textarea', $name, $label);
    }

    /**
     * Dodaje pole edytora wysiwyg do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @return self
     */
    public function addImprintElementTinymce($name, $label)
    {
        return $this->addImprintElement('tinymce', $name, $label);
    }

    /**
     * Dodaje pole checkbox do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @return self
     */
    public function addImprintElementCheckbox($name, $label)
    {
        return $this->addImprintElement('checkbox', $name, $label);
    }

    /**
     * Dodaje pole listy do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @param array $option opcje
     * @return self
     */
    public function addImprintElementSelect($name, $label, $option)
    {
        return $this->addImprintElement('select', $name, $label, $option);
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $this->view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.css');
        $this->view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/jquery-ui/jquery-ui.structure.min.css');
        $this->view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/jquery-ui/jquery-ui.theme.min.css');
        $this->view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/plupload/plupload.conf.css');
        $this->view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css');
        $this->view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/video-frame-extractor/extractor.css');
        $this->view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/vendors/js/popper.min.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/vendors/js/pace.min.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/vendors/js/bootstrap.min.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/plupload.full.min.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/plupload.conf.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/i18n/pl.js');
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js');
        //imprint zawiera tinymce
        if (strpos(print_r($this->getImprint(), true), 'tinymce')) {
            $this->view->headScript()->appendFile('/resource/cmsAdmin/js/tiny/tinymce.min.js');
        }
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/video-frame-extractor/extractor.js');

        $html = '<div id="' . $this->getId() . '">';
        $html .= '<p>Twoja przeglądarka nie posiada wsparcia dla HTML5.</p>';
        $html .= '<p>Proszę zaktualizować oprogramowanie.</p>';
        $html .= '</div>';
        $html .= '<div id="' . $this->getId() . '-confirm" class="plupload-confirm-container" title="">';
        $html .= '<p><span class="dialog-info"></span><span class="dialog-file"></span><span class="dialog-info-2"></span></p>';
        $html .= '</div>';
        $html .= '<div id="' . $this->getId() . '-edit" class="plupload-edit-container" title="">';
        $html .= '<fieldset>';
        $html .= $this->_renderImprintElements();
        $html .= '<div class="plupload-edit-buttons">';
        $html .= '<label for="' . $this->getId() . '-edit-original">nazwa pliku:</label>';
        $html .= '<input type="text" class="text imprint" name="original" id="' . $this->getId() . '-edit-userFileName">';
        $html .= '</div>';
        $html .= '<div id="' . $this->getId() . '-edit-buttons" class="plupload-edit-buttons">';
        $html .= '<input type="checkbox" name="active" id="' . $this->getId() . '-edit-active" value="1"><label for="' . $this->getId() . '-edit-active">aktywny</label>';
        $html .= '</div>';
        $html .= '</fieldset>';
        $html .= '<div class="dialog-error"><p></p><span class="ui-icon ui-icon-alert"></span></div>';
        $html .= '</div>';
        if ($this->getOption('showConsole')) {
            $html .= '<div class="plupload-log-container">';
            $html .= '<pre class="plupload-log-console" id="' . $this->getId() . '-console"></pre>';
            $html .= '</div>';
        }

        //dołączanie skryptu
        $this->view->headScript()->appendScript("
			$(document).ready(function () {
				'use strict';
				var conf = $.extend(true, {}, PLUPLOADCONF.settings);
				conf.form_element_id = '" . $this->getId() . "';
				conf.form_object = '" . self::TEMP_OBJECT_PREFIX . $this->getObject() . "';
				conf.form_object_id = '" . $this->getUploaderId() . "';
				" . ($this->getOption('showConsole') ? "conf.log_element = '" . $this->getId() . "-console';" : "") . "
				" . ($this->getOption('chunkSize') ? "conf.chunk_size = '" . $this->getOption('chunkSize') . "';" : "") . "
				" . ($this->getOption('maxFileSize') ? "conf.max_file_size = '" . $this->getOption('maxFileSize') . "';" : "") . "
				" . ($this->getOption('maxFileCount') ? "conf.max_file_cnt = " . $this->getOption('maxFileCount') . ";" : "") . "
				" . ($this->getOption('mimeTypes') ? "conf.filters.mime_types = " . json_encode($this->getOption('mimeTypes')) . ";" : "") . "
				" . ($this->getOption('fileTypes') ? "conf.file_types = '" . $this->getOption('fileTypes') . "';" : "") . "
				" . ($this->getOption('afterUpload') ? "conf.after_upload = " . json_encode($this->getOption('afterUpload')) . ";" : "") . "
				" . ($this->getOption('afterDelete') ? "conf.after_delete = " . json_encode($this->getOption('afterDelete')) . ";" : "") . "
				conf.preview = " . ($this->getOption('preview') ? $this->getOption('preview') : 0) . ";
                conf.poster = " . ($this->getOption('poster') ? $this->getOption('poster') : 1) . ";
				" . ($this->getOption('afterEdit') ? "conf.after_edit = " . json_encode($this->getOption('afterEdit')) . ";" : "") . "
				$('#" . $this->getId() . "').plupload(conf);
			});
		");

        return $html;
    }

    /**
     * Rendering elementów metryczki
     * @return string
     */
    protected function _renderImprintElements()
    {
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
    protected function _renderImprintElement($element)
    {
        //walidacja
        if (!isset($element['type']) || !isset($element['name'])) {
            return '';
        }
        //identyfikator pola
        $fieldId = $this->getId() . '-' . (new Url())->filter($element['name']);

        //input poster video
        if ($element['type'] == 'poster') {
            $this->setOption('poster', true);
            $html = "
                <div class='frame-extractor'>
                    <div class='row'>
                        <div class='col d-flex justify-content-center video-container'>
                            <video id='video' controls=''>
                                <source  id='urlVideo' type='video/mp4'>
                                <p>Twója przeglądarka nie obsługuje video w formacie mp4.</p>
                            </video>
                            <input id='poster' type='hidden' name='poster' value=''/>
                            <button id='frame-camera' type='button' class='btn btn-outline-primary frame-camera' data-toggle='tooltip' data-placement='top' title='Złap aktualną ramkę z wideo'>
                                <i class='fa fa-2 fa-camera'></i>
                            </button>
                            <button id='frame-upload' type='button'  data-toggle='tooltip' data-placement='top' title='Prześlij swoją ramkę wideo. Rozmiar obraka musi być conajmniej takiego rozmiaru jak wgrane wideo.' class='btn btn-outline-primary frame-upload'>
                                <i class='fa fa-2 fa-upload'></i>
                            </button>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col'>
                            <input type='file' name='userPoster' id='userPoster' accept='image/*'>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col'>
                            <div id='output'>
                            </div>
                        </div>
                    </div>
                </div>
			";
            return $html;
        }

        //walidacja
        if (!isset($element['label'])) {
            return '';
        }

        //element label
        $label = '<div class="plupload-edit-buttons"><label for="' . $fieldId . '">' . $element['label'] . (($element['type'] != 'checkbox') ? ':' : '') . '</label>';
        //input text
        if ($element['type'] == 'text') {
            return $label .
                '<input id="' . $fieldId . '" type="' . $element['type'] . '" name="' . $element['name'] . '" class="imprint ' . $element['type'] . '"></div>';
        }
        //input checkbox
        if ($element['type'] == 'checkbox') {
            return '<div class="plupload-edit-buttons"><input id="' . $fieldId . '" type="' . $element['type'] . '" name="' . $element['name'] . '" value="1" class="imprint ' . $element['type'] . '"><label for="' . $fieldId . '">' . $element['label'] . (($element['type'] != 'checkbox') ? ':' : '') . '</label></div>';
        }
        //textarea
        if ($element['type'] == 'textarea') {
            return $label .
                '<textarea id="' . $fieldId . '" name="' . $element['name'] . '" class="imprint ' . $element['type'] . '"></textarea></div>';
        }
        //tinymce
        if ($element['type'] == 'tinymce') {
            return $label .
                '<textarea id="' . $fieldId . '" name="' . $element['name'] . '" class="plupload-edit-tinymce imprint ' . $element['type'] . '"></textarea></div>';
        }
        //lista
        if ($element['type'] == 'select') {
            $option = [];
            foreach ($element['options'] as $key => $value) {
                if (is_array($value)) {
                    $data = '';
                    foreach ($value['data'] as $data_key => $data_val) {
                        $data .= str_replace('_', '-', $data_key) . '="' . $data_val . '"';
                    }
                    array_push($option, '<option value="' . $key . '" ' . $data . '>' . $value['value'] . '</option>');
                } else {
                    array_push($option, '<option value="' . $key . '">' . $value . '</option>');
                }
            }

            //dodaje podglad obrazka
            $image = '';
            if (isset($element['preview'])) {
                $this->setOption('preview', true);

                $image = "
				<div class='col_3' id='image_$fieldId'></div>
				<script type='text/javascript'>
				// <![CDATA[
					$(document).ready(function () {
						'use strict';
						$('#$fieldId').change(function() {
							var src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
							if( $(this).find(':selected').attr('data-image-url') != undefined ){
								src = $(this).find(':selected').attr('data-image-url')
							}

							$('#image_$fieldId').html( images + src +'\"/>' );
						});
					});
				// ]]>
				</script>
				<div class='col_12'></div><br clear='both'/>
				";
            }

            return $label .
                '<select id="' . $fieldId . '" name="' . $element['name'] . '" class="imprint ' . $element['type'] . ($image != '' ? ' col_9' : '') . '">' . implode($option) . '</select></div>' . $image;
        }
        return '';
    }
}
