<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

use Mmi\App\App;
use Mmi\Session\Session;

/**
 * Element tinymce
 * 
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
class TinyMce extends UploaderElementAbstract
{

    //szablon początku pola
    const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon końca pola
    const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';
    //szablon pola textarea
    const TEMPLATE_FIELD = 'mmi/form/element/textarea';
    //przedrostek tymczasowego obiektu plików
    const TEMP_OBJECT_PREFIX = 'tmp-';

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
     * Alias na setObject()s
     * @param string $object
     * @return self
     */
    public function setUploaderObject($object)
    {
        return $this->setObject($object);
    }

    /**
     * Ustawia tryb zaawansowany
     * @return \Cms\Form\Element\TinyMce
     */
    public function setModeAdvanced()
    {
        return $this->setOption('mode', 'advanced');
    }

    /**
     * Ustawia tryb domyślny
     * @return \Cms\Form\Element\TinyMce
     */
    public function setModeDefault()
    {
        return $this->setOption('mode', null);
    }

    /**
     * Ustawia tryb prosty
     * @return \Cms\Form\Element\TinyMce
     */
    public function setModeSimple()
    {
        return $this->setOption('mode', 'simple');
    }

    /**
     * Ustawia tryb własny
     * @param string $mode własna konfiguracja
     * @return \Cms\Form\Element\TinyMce
     */
    public function setMode($mode)
    {
        return $this->setOption('mode', $mode);
    }

    /**
     * Ustawia szerokość w px
     * @param int $width
     * @return \Cms\Form\Element\TinyMce
     */
    public function setWidth($width)
    {
        return $this->setOption('width', intval($width));
    }

    /**
     * Ustawia wysokość w px
     * @param int $height
     * @return \Cms\Form\Element\TinyMce
     */
    public function setHeight($height)
    {
        return $this->setOption('height', intval($height));
    }

    /**
     * Ustawia dodatkowe parametry do konfiguracji - RAW zgodne z dokumentacją TinyMce
     * klucz_tiny1: wartosc1, klucz_tiny2: wartosc2
     * @param string $custom
     * @return \Cms\Form\Element\TinyMce
     */
    public function setCustomConfig($custom)
    {
        return $this->setOption('customConfig', $custom);
    }

    /**
     * Powołanie pola
     * @param type $name
     */
    public function __construct($name)
    {
        $this->addClass('form-control');
        parent::__construct($name);
        //wyłączenie CDN
        $this->view->setCdn(null);
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/tiny/tinymce.min.js');

        //bazowa wspólna konfiguracja
        $this->_baseConfig($this->view);
        //tryb edytora
        $mode = $this->getMode() ? $this->getMode() : 'default';
        //metoda konfiguracji edytora
        $modeConfigurator = '_mode' . ucfirst($mode);
        if (method_exists($this, $modeConfigurator)) {
            $this->$modeConfigurator();
        }

        $class = $this->getOption('id');
        $this->setOption('class', trim($this->getOption('class') . ' ' . $class));
        $object = self::TEMP_OBJECT_PREFIX . $this->getObject();
        $objectId = $this->getUploaderId();
        $t = round(microtime(true));
        $hash = md5(App::$di->get(Session::class)->getId() . '+' . $t . '+' . $objectId);
        //tworzenie kopii plików załadowanych do TinyMce
        $this->_createTempFiles();
        //dołączanie skryptu
        $this->view->headScript()->appendScript("
			tinyMCE.init({
				selector: '." . $class . "',
				language: request.locale,
				" . $this->_renderConfig('theme', 'theme', 'modern') . "
				" . $this->_renderConfig('skin', 'skin', 'lightgray') . "
				" . $this->_renderConfig('plugins', 'plugins') . "
				" . $this->_renderConfig('contextmenu', 'contextMenu') . "
				" . $this->_renderConfig('width', 'width', '') . "
				" . $this->_renderConfig('height', 'height', 320) . "
				" . $this->_renderConfig('resize', 'resize', true) . "
				" . $this->_renderConfig('menubar', 'menubar', true) . "
				" . $this->_renderConfig('image_advtab', 'imageAdvanceTab', true) . "
				" . $this->_renderConfig('font_formats', 'fontFormats') . "
				" . $this->_renderConfig('fontsize_formats', 'fontSizeFormats') . "
				" . $this->_renderConfig('content_css', 'css') . "
				" . $this->_renderConfigN('toolbar', 'toolbars') . "
				" . $this->_renderConfig('image_caption', 'imageCaption', false) . "
				" . ($this->getCustomConfig() ? trim($this->getCustomConfig(), ",") . "," : "") . "
				" . $this->_other . "
				" . $this->_common . "
				hash: '$hash',
				object: '$object',
				objectId: '$objectId',
				time: '$t',
				baseUrl: '" . $this->view->baseUrl . "',
                image_list: '" . $this->view->baseUrl . "' + '/?module=cms&controller=file&action=list&object=$object&objectId=$objectId&t=$t&hash=$hash',
                branding: false
			});
		");

        //unsety zbędnych opcji
        $this->unsetMode()->unsetCustomConfig()->unsetCss()->unsetTheme()->unsetSkin()
            ->unsetPlugins()->unsetContextMenu()->unsetResize()->unsetMenubar()
            ->unsetImageAdvanceTab()->unsetFontFormats()->unsetFontSizeFormats()
            ->unsetToolbars()->unsetImageCaption();

        return parent::fetchField();
    }

    /**
     * Renderuje opcję konfiguracji TinyMce na podstawie opcji pola formularza
     * @param string $tinyKey klucz konfiguracji edytora TinyMce
     * @param string $optionKey klucz opcji formularza
     * @param mixed $defaultVal wartość domyślna
     * @return string
     */
    protected function _renderConfig($tinyKey, $optionKey, $defaultVal = null)
    {
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
            $tinyKey .= "'" . trim($optionVal, "'") . "'";
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
     * Renderuje wielowartościową opcję konfiguracji TinyMce na podstawie opcji pola formularza
     * @param string $tinyKeyPrefix prefiks klucza konfiguracji edytora TinyMce
     * @param string $optionKey klucz opcji formularza
     * @return string
     */
    protected function _renderConfigN($tinyKeyPrefix, $optionKey)
    {
        if (null === $optionVal = $this->getOption($optionKey)) {
            return "";
        }
        if (!is_array($optionVal)) {
            $optionVal = [$optionVal];
        }
        $confN = "";
        foreach ($optionVal as $index => $val) {
            $confN .= $tinyKeyPrefix . ($index + 1) . ": " . "'" . trim($val, "'") . "',\r\n";
        }
        return trim($confN, ",\r\n") . ",";
    }

    /**
     * Bazowa konfiguracja dla wszystkich edytorów
     */
    protected function _baseConfig(\Mmi\Mvc\View $view)
    {
        if ($this->getPlugins() === null) {
            $this->setPlugins([
                'lioniteimages,advlist,anchor,autolink,autoresize,charmap,code,contextmenu,fullscreen',
                'hr,image,insertdatetime,link,lists,media,nonbreaking,noneditable,paste,print,preview',
                'searchreplace,tabfocus,table,textcolor,visualblocks,visualchars,wordcount'
            ]);
        }
        if ($this->getFontFormats() === null) {
            $this->setFontFormats("'Andale Mono=andale mono,times;'+
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
				'Wingdings=wingdings,zapf dingbats'");
        }
        if ($this->getFontSizeFormats() === null) {
            $this->setFontSizeFormats('4px 6px 8px 9pc 10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 36px 48px 50px 72px 100px');
        }
        $this->_common = "
			autoresize_min_height: " . ($this->getHeight() ? $this->getHeight() : 300) . ",
			document_base_url: '" . $view->baseUrl . "',
			convert_urls: false,
			entity_encoding: 'raw',
			relative_urls: false,
			paste_data_images: false,
			plugin_preview_height: 700,
			plugin_preview_width: 1100,
            image_class_list: [
                {title: 'Obrazek do lewej', value: 'image-left'},
                {title: 'Obrazek do prawej', value: 'image-right'},
            ],
		";
    }

    /**
     * Konfiguracja dla trybu Simple
     */
    protected function _modeSimple()
    {
        if ($this->getToolbars() === null) {
            $this->setToolbars('bold italic underline strikethrough | alignleft aligncenter alignright alignjustify');
        }
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
    protected function _modeAdvanced()
    {
        if ($this->getToolbars() === null) {
            $this->setToolbars([
                'undo redo | cut copy paste pastetext | searchreplace | bold italic underline strikethrough | subscript superscript | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect | forecolor backcolor',
                'styleselect | table | bullist numlist outdent indent blockquote | link unlink anchor | image media lioniteimages | preview fullscreen code | charmap visualchars nonbreaking inserttime hr'
            ]);
        }
        if ($this->getContextMenu() === null) {
            $this->setContextMenu('link image media inserttable | cell row column deletetable');
        }
    }

    /**
     * Konfiguracja dla trybu Default
     */
    protected function _modeDefault()
    {
        if ($this->getToolbars() === null) {
            $this->setToolbars('undo redo | bold italic underline strikethrough | forecolor backcolor | styleselect | bullist numlist outdent indent | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | link unlink anchor | image media lioniteimages | preview');
        }
        if ($this->getContextMenu() === null) {
            $this->setContextMenu('link image media inserttable | cell row column deletetable');
        }
    }

}
