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
 */
class Plupload extends \Mmi\Form\Element\ElementAbstract
{

    //szablon początku pola
    CONST TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    CONST TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    CONST TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    CONST TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    CONST TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';
    //klucz z losowym id uploadera
    CONST UPLOADER_ID_KEY = 'uploaderId';

    /**
     * Id elementu formularza
     * @var string
     */
    protected $_id = '';

    /**
     * Typ obiektu
     * @var string
     */
    protected $_object = 'library';

    /**
     * Id obiektu
     * @var integer
     */
    protected $_objectId = null;

    /**
     * Tymczsowy typ obiektu
     * @var string
     */
    protected $_tempObject = 'library';

    /**
     * Ustawia form macierzysty
     * @param \Mmi\Form\Form $form
     * @return self
     */
    public function setForm(\Mmi\Form\Form $form)
    {
        //parent
        parent::setForm($form);
        //obiekt niezdefiniowany
        if (!$this->getObject()) {
            //ustawianie obiektu
            $this->setObject($form->getFileObjectName());
        }
        //instancja front controllera
        $frontController = \Mmi\App\FrontController::getInstance();
        //uploaderId znajduje się w requescie
        if ($frontController->getRequest()->uploaderId) {
            $this->setOption(self::UPLOADER_ID_KEY, $frontController->getRequest()->uploaderId);
            return $this;
        }
        //przekierowanie na url zawierający nowowygenerowany uploaderId
        $frontController->getResponse()->redirectToUrl($frontController->getView()->url($frontController->getRequest()->toArray() + ['uploaderId' => mt_rand(1000000, 9999999)]));
        return $this;
    }

    /**
     * Pobranie ID uploadera
     * @return string
     */
    public function getUploaderId()
    {
        return $this->getOption(self::UPLOADER_ID_KEY);
    }

    /**
     * Zwraca obiekt uploadera
     * @return void
     */
    public function getUploaderObject()
    {
        return $this->getObject();
    }

    /**
     * Ustawia objekt cms_
     * @param string $object
     * @return \Cms\Form\Element\Plupload
     */
    public function setObject($object)
    {
        return $this->setOption('object', $object);
    }

    /**
     * Ustawia rozmiar chunka
     * @param string $size
     * @return \Cms\Form\Element\Plupload
     */
    public function setChunkSize($size)
    {
        return $this->setOption('chunkSize', $size);
    }

    /**
     * Ustawia maksymalny rozmiar pliku
     * @param string $size
     * @return \Cms\Form\Element\Plupload
     */
    public function setMaxFileSize($size)
    {
        return $this->setOption('maxFileSize', $size);
    }

    /**
     * Ustawia maksymalną ilość plików możliwą do wgrania
     * @param integer $count
     * @return \Cms\Form\Element\Plupload
     */
    public function setMaxFileCount($count)
    {
        return $this->setOption('maxFileCount', intval($count));
    }

    /**
     * Ustawia, czy pokazać konsolę z komunikatami
     * @param boolean $show
     * @return \Cms\Form\Element\Plupload
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
     * @return \Cms\Form\Element\Plupload
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
     * @return \Cms\Form\Element\Plupload
     */
    public function addAllowedJpg()
    {
        return $this->addAllowedType('image/jpeg', 'jpg,jpeg,jpe');
    }

    /**
     * Dodaje dozwolony typ pliku PNG
     * @return \Cms\Form\Element\Plupload
     */
    public function addAllowedPng()
    {
        return $this->addAllowedType('image/png', 'png');
    }

    /**
     * Dodaje dozwolony typ pliku GIF
     * @return \Cms\Form\Element\Plupload
     */
    public function addAllowedGif()
    {
        return $this->addAllowedType('image/gif', 'gif');
    }

    /**
     * Ustawia, że z aktualnej listy plików wyświetla tylko obrazki
     * @return \Cms\Form\Element\Plupload
     */
    public function setTypeImages()
    {
        return $this->setOption('fileTypes', 'images');
    }

    /**
     * Ustawia, że z aktualnej listy plików wyświetla wszystkie poza obrazkami
     * @return \Cms\Form\Element\Plupload
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
     * @return \Cms\Form\Element\Plupload
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
     * @return \Cms\Form\Element\Plupload
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
     * @return \Cms\Form\Element\Plupload
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
     * @return \Cms\Form\Element\Plupload
     */
    public function addImprintElement($type, $name, $label = null, $options = [])
    {
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
    public function addImprintElementText($name, $label)
    {
        return $this->addImprintElement('text', $name, $label);
    }

    /**
     * Dodaje pole textarea do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @return \Cms\Form\Element\Plupload
     */
    public function addImprintElementTextarea($name, $label)
    {
        return $this->addImprintElement('textarea', $name, $label);
    }

    /**
     * Dodaje pole edytora wysiwyg do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @return \Cms\Form\Element\Plupload
     */
    public function addImprintElementTinymce($name, $label)
    {
        return $this->addImprintElement('tinymce', $name, $label);
    }

    /**
     * Dodaje pole checkbox do metryczki
     * @param string $name nazwa pola
     * @param string $label labelka pola
     * @return \Cms\Form\Element\Plupload
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
     * @return \Cms\Form\Element\Plupload
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
        $view = \Mmi\App\FrontController::getInstance()->getView();
        $view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.css');
        $view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/jquery-ui/jquery-ui.structure.min.css');
        $view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/jquery-ui/jquery-ui.theme.min.css');
        $view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/plupload/plupload.conf.css');
        $view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css');
        $view->headLink()->appendStyleSheet('/resource/cmsAdmin/js/video-frame-extractor/extractor.css');
        $view->headScript()->prependFile('/resource/cmsAdmin/js/jquery/jquery.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/vendors/js/popper.min.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/vendors/js/pace.min.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/vendors/js/bootstrap.min.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/js/jquery-ui/jquery-ui.min.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/plupload.full.min.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/plupload.conf.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/i18n/pl.js');
        $view->headScript()->appendFile('/resource/cmsAdmin/js/plupload/jquery.ui.plupload/jquery.ui.plupload.min.js');
        //imprint zawiera tinymce
        if (strpos(print_r($this->getImprint(), true), 'tinymce')) {
            $view->headScript()->appendScript("var tinymce = tinymce || {};");
            $view->headScript()->appendFile('/resource/cmsAdmin/js/tiny/tinymce.min.js');
        }
        $view->headScript()->appendFile('/resource/cmsAdmin/js/video-frame-extractor/extractor.js');

        //przygotowanie danych dla pola
        $this->_beforeRender();

        $html = '<div id="' . $this->_id . '">';
        $html .= '<p>Twoja przeglądarka nie posiada wsparcia dla HTML5.</p>';
        $html .= '<p>Proszę zaktualizować oprogramowanie.</p>';
        $html .= '</div>';
        $html .= '<div id="' . $this->_id . '-confirm" class="plupload-confirm-container" title="">';
        $html .= '<p><span class="dialog-info"></span><span class="dialog-file"></span><span class="dialog-info-2"></span></p>';
        $html .= '</div>';
        $html .= '<div id="' . $this->_id . '-edit" class="plupload-edit-container" title="">';
        $html .= '<fieldset>';
        $html .= $this->_renderImprintElements();
        $html .= '<label for="' . $this->_id . '-edit-original">nazwa wyświetlana</label>';
        $html .= '<input type="text" class="text imprint" name="original" id="' . $this->_id . '-edit-userFileName">';
        $html .= '<div id="' . $this->_id . '-edit-buttons" class="plupload-edit-buttons">';
        $html .= '<input type="checkbox" name="active" id="' . $this->_id . '-edit-active" value="1"><label for="' . $this->_id . '-edit-active">aktywny</label>';
        $html .= '<input type="checkbox" name="sticky" id="' . $this->_id . '-edit-sticky" value="1"><label for="' . $this->_id . '-edit-sticky">wyróżniony</label>';
        $html .= '</div>';
        $html .= '</fieldset>';
        $html .= '<div class="dialog-error"><p></p><span class="ui-icon ui-icon-alert"></span></div>';
        $html .= '</div>';
        if ($this->getOption('showConsole')) {
            $html .= '<div class="plupload-log-container">';
            $html .= '<pre class="plupload-log-console" id="' . $this->_id . '-console"></pre>';
            $html .= '</div>';
        }

        //dołączanie skryptu
        $view->headScript()->appendScript("
			$(document).ready(function () {
				'use strict';
				var conf = $.extend(true, {}, PLUPLOADCONF.settings);
				conf.form_element_id = '$this->_id';
				conf.form_object = '$this->_tempObject';
				conf.form_object_id = '" . $this->getUploaderId() . "';
				" . ($this->getOption('showConsole') ? "conf.log_element = '" . $this->_id . "-console';" : "") . "
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
				$('#$this->_id').plupload(conf);
			});
		");

        return $html;
    }

    /**
     * Przygotowanie danych przed renderingiem pola formularza
     * @return \Cms\Form\Element\Plupload
     */
    protected function _beforeRender()
    {
        $this->_id = $this->getOption('id');
        if ($this->_form->hasRecord()) {
            $this->_object = $this->_form->getFileObjectName();
            $this->_objectId = $this->_form->getRecord()->getPk();
        }
        //jeśli wymuszony inny object
        if ($this->getOption('object')) {
            $this->_object = $this->getOption('object');
        }
        $this->_tempObject = 'tmp-' . $this->_object;
        $this->_createTempFiles();
        return $this;
    }

    /**
     * Utorzenie kopii plików dla tego uploadera
     * @return boolean
     */
    protected function _createTempFiles()
    {
        //jeśli już są pliki tymczasowe, to wychodzimy
        if ((new \Cms\Orm\CmsFileQuery)
                ->byObject($this->_tempObject, $this->getUploaderId())
                ->count()) {
            return true;
        }
        //tworzymy pliki tymczasowe - kopie oryginałów
        \Cms\Model\File::link($this->_object, $this->_objectId, $this->_tempObject, $this->getUploaderId());
        return true;
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
            return;
        }
        //identyfikator pola
        $fieldId = $this->getId() . '-' . (new \Mmi\Filter\Url)->filter($element['name']);

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
            return;
        }
        //element label
        $label = ' <div class="form-group"><label for="' . $fieldId . '">' . $element['label'] . (($element['type'] != 'checkbox') ? ':' : '') . '</label>';
        //input text
        if ($element['type'] == 'text') {
            return $label .
                '<input id="' . $fieldId . '" type="' . $element['type'] . '" name="' . $element['name'] . '" class="imprint ' . $element['type'] . '"></div>';
        }
        //input checkbox
        if ($element['type'] == 'checkbox') {
            return '<input id="' . $fieldId . '" type="' . $element['type'] . '" name="' . $element['name'] . '" value="1" class="imprint ' . $element['type'] . '">' . $label;
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
                    array_push($option, '<option value="' . $key . '" ' . $data . '>' . $value['value'] . '</option>"');
                } else {
                    array_push($option, '<option value="' . $key . '">' . $value . '</option>"');
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
                '<select id="' . $fieldId . '" name="' . $element['name'] . '" class="' . ($image != '' ? 'col_9' : '') . ' plupload-edit-tinymce imprint ' . $element['type'] . '">' . implode($option) . '</select></div>' . $image;
        }
    }

}
