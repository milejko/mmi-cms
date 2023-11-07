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
use Mmi\Form\Form;
use Mmi\Mvc\View;
use Mmi\Session\SessionInterface;

/**
 * Element tinymce
 *
 * Gettery
 * @method string getObject() pobiera obiekt
 * @method int getObjectId() pobiera identyfikator obiektu
 * @method int getUploaderId() pobiera identyfikator uploadera
 * @method ?bool getResize()
 * @method ?int getHeight()
 * @method ?int getWidth()
 * @method ?string getToolbars()
 * @method ?string getContextMenu()
 * @method ?string getMenubar()
 * @method ?string getFontSizeFormats()
 * @method ?string getMode()
 * @method ?string getFontFormats()
 * @method ?string getPlugins()
 * @method ?string getCustomConfig()
 *
 * Settery
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($id) ustawia identyfikator obiektu
 * @method self setUploaderId($id) ustawia id uploadera
 * @method self setContextMenu(string $value)
 * @method self setToolbars(string $value)
 * @method self setMenubar(string $value)
 * @method self setPlugins(string $value)
 * @method self setHeight(int $value)
 * @method self setWidth(int $value)
 * @method self setFontFormats(string $value)
 * @method self setResize(string $value)
 * @method self setFontSizeFormats(string $value)
 * @method self unsetMode()
 */
class TinyMce extends UploaderElementAbstract
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
    //szablon pola textarea
    public const TEMPLATE_FIELD = 'mmi/form/element/textarea';

    public const TOOLBARS = [
        'simple'   => [
            'undo redo | bold italic underline strikethrough | link unlink anchor | alignleft aligncenter alignright alignjustify | subscript superscript | charmap visualchars nonbreaking mathlive',
        ],
        'advanced' => [
            'undo redo | cut copy paste pastetext searchreplace | bold italic underline strikethrough | subscript superscript | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect | forecolor backcolor',
            'styleselect | table | bullist numlist outdent indent blockquote | link unlink anchor | image media lioniteimages | fullscreen code | charmap visualchars nonbreaking hr mathlive',
        ],
    ];

    public const CONTEXT_MENU = [
        'simple'   => 'link image inserttable | cell row column deletetable',
        'advanced' => 'link image media inserttable | cell row column deletetable',
    ];

    public const PLUGINS = [
        'lioniteimages,advlist,anchor,autolink,autoresize,charmap,code,contextmenu,fullscreen',
        'hr,image,insertdatetime,link,lists,media,nonbreaking,noneditable,paste,print,preview',
        'searchreplace,tabfocus,table,textcolor,visualblocks,visualchars,wordcount,mathlive',
    ];

    public const FONT_FORMATS = [
        'Andale Mono=andale mono,times',
        'Arial=arial,helvetica,sans-serif',
        'Arial Black=arial black,avant garde',
        'Book Antiqua=book antiqua,palatino',
        'Comic Sans MS=comic sans ms,sans-serif',
        'Courier New=courier new,courier',
        'Georgia=georgia,palatino',
        'Helvetica=helvetica',
        'Impact=impact,chicago',
        'Symbol=symbol',
        'Tahoma=tahoma,arial,helvetica,sans-serif',
        'Terminal=terminal,monaco',
        'Times New Roman=times new roman,times',
        'Trebuchet MS=trebuchet ms,geneva',
        'Verdana=verdana,geneva',
        'Webdings=webdings',
        'Wingdings=wingdings,zapf dingbats',
    ];

    public const FONT_SIZES = '4px 6px 8px 9px 10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 36px 48px 50px 72px 100px';

    public const IMAGE_CLASS_LIST      = [
        [
            'title' => 'Obrazek do lewej',
            'value' => 'image-left',
        ],
        [
            'title' => 'Obrazek do prawej',
            'value' => 'image-right',
        ],
        [
            'title' => 'Zoom',
            'value' => 'image-zoom',
        ],
    ];
    public const PLUGIN_PREVIEW_HEIGHT = 700;
    public const PLUGIN_PREVIEW_WIDTH  = 1100;

    /**
     * Wspóle ustawienia dla wszystkich trybów
     *
     * @var array
     */
    protected $_common;

    /**
     * Ustawia form macierzysty
     *
     * @param Form $form
     *
     * @return self
     */
    public function setForm(Form $form)
    {
        parent::setForm($form);
        $this->setIgnore(false);

        return $this;
    }

    /**
     * Alias na setObject()s
     *
     * @param string $object
     *
     * @return self
     */
    public function setUploaderObject($object)
    {
        return $this->setObject($object);
    }

    /**
     * Ustawia tryb domyślny
     *
     * @return TinyMce
     */
    public function setModeDefault()
    {
        return $this->setOption('mode', 'advanced');
    }

    /**
     * Ustawia tryb zaawansowany/techniczny
     *
     * @return TinyMce
     */
    public function setModeAdvanced()
    {
        return $this->setOption('mode', 'advanced');
    }

    /**
     * Ustawia tryb prosty
     *
     * @return TinyMce
     */
    public function setModeSimple()
    {
        return $this->setOption('mode', 'simple');
    }

    /**
     * Ustawia tryb własny
     *
     * @param string $mode własna konfiguracja
     *
     * @return TinyMce
     */
    public function setMode($mode)
    {
        return $this->setOption('mode', $mode);
    }

    /**
     * Ustawia szerokość w px
     *
     * @param int $width
     *
     * @return TinyMce
     */
    public function setWidth($width)
    {
        return $this->setOption('width', intval($width));
    }

    /**
     * Ustawia wysokość w px
     *
     * @param int $height
     *
     * @return TinyMce
     */
    public function setHeight($height)
    {
        return $this->setOption('height', intval($height));
    }

    /**
     * Ustawia dodatkowe parametry do konfiguracji - RAW zgodne z dokumentacją TinyMce
     * klucz_tiny1: wartosc1, klucz_tiny2: wartosc2
     *
     * @param string $custom
     *
     * @return TinyMce
     */
    public function setCustomConfig($custom)
    {
        return $this->setOption('customConfig', $custom);
    }

    /**
     * Powołanie pola
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->addClass('form-control');
        $this->addClass('tinymce');
        parent::__construct($name);
        //wyłączenie CDN
        $this->view->setCdn(null);
    }

    /**
     * Buduje pole
     *
     * @return string
     */
    public function fetchField()
    {
        $this->view->headScript()->appendFile('/resource/cmsAdmin/js/tiny/tinymce.min.js');

        //bazowa wspólna konfiguracja
        $this->_baseConfig($this->view);
        //tryb edytora
        $mode = $this->getMode() ?? 'advanced';
        //metoda konfiguracji edytora
        $modeConfigurator = '_mode' . ucfirst($mode);
        if (method_exists($this, $modeConfigurator)) {
            $this->$modeConfigurator();
        }
        $config = $this->_renderConfig();
        $this->setOption('data-config', $config);
        //tworzenie kopii plików załadowanych do TinyMce
        $this->_createTempFiles();
        //dołączanie skryptu
        $this->view->headScript()->appendScript(
            "
            $('.tinymce').each(function () {
                let configJson = $(this).data('config');
                configJson.language = request.locale;
                tinyMCE.init(configJson);
            });
        "
        );

        //unsety zbędnych opcji
        $this->unsetMode()->unsetCustomConfig()->unsetCss()->unsetTheme()->unsetSkin()
            ->unsetPlugins()->unsetContextMenu()->unsetResize()->unsetMenubar()
            ->unsetImageAdvanceTab()->unsetFontFormats()->unsetFontSizeFormats()
            ->unsetImageCaption();

        return parent::fetchField();
    }

    private function _renderConfig()
    {
        $class = $this->getOption('id');
        $this->setOption('class', trim($this->getOption('class') . ' ' . $class));
        $object   = self::TEMP_OBJECT_PREFIX . $this->getObject();
        $objectId = $this->getUploaderId();
        $time     = round(microtime(true));
        $hash     = md5(App::$di->get(SessionInterface::class)->getId() . '+' . $time . '+' . $objectId);

        $config = [
            'selector'          => '.' . $class,
            'theme'             => $this->_renderConfigOption('theme', 'modern'),
            'skin'              => $this->_renderConfigOption('skin', 'lightgray'),
            'plugins'           => $this->_renderConfigOption('plugins'),
            'contextmenu'       => $this->_renderConfigOption('contextMenu'),
            'width'             => $this->_renderConfigOption('width', ''),
            'height'            => $this->_renderConfigOption('height', 320),
            'resize'            => $this->_renderConfigOption('resize', true),
            'menubar'           => $this->_renderConfigOption('menubar', true),
            'image_advtab'      => $this->_renderConfigOption('imageAdvanceTab', true),
            'font_formats'      => $this->_renderConfigOption('fontFormats'),
            'fontsize_formats'  => $this->_renderConfigOption('fontSizeFormats'),
            'content_css'       => [
                '/resource/cmsAdmin/js/tiny/plugins/mathlive/mathlive/mathlive.css',
            ],
            'extended_valid_elements' => 'span[*]',
            'image_caption'     => $this->_renderConfigOption('imageCaption', false),
            'hash'              => $hash,
            'object'            => $object,
            'objectId'          => $objectId,
            'time'              => $time,
            'image_list'        => '/?module=cms&controller=file&action=list&object=' . $object . '&objectId=' . $objectId . '&t=' . $time . '&hash=' . $hash,
            'branding'          => false,
            'force_br_newlines' => false,
            'force_p_newlines'  => false,
            'forced_root_block' => '',
        ];
        $config = array_merge($config, $this->_renderConfigOptionN('toolbar', 'toolbars'), $this->_common, $this->getCustomConfig() ?? []);
        return json_encode($config);
    }

    /**
     * Renderuje opcję konfiguracji TinyMce na podstawie opcji pola formularza
     *
     * @param string $optionKey  klucz opcji formularza
     * @param mixed  $defaultVal wartość domyślna
     *
     * @return mixed
     */
    protected function _renderConfigOption(string $optionKey, $defaultVal = null)
    {
        if (null === $optionVal = $this->getOption($optionKey)) {
            if ($defaultVal === null) {
                return "";
            }
            $optionVal = $defaultVal;
        }

        return $optionVal;
    }

    /**
     * Renderuje wielowartościową opcję konfiguracji TinyMce na podstawie opcji pola formularza
     *
     * @param string $tinyKeyPrefix prefiks klucza konfiguracji edytora TinyMce
     * @param string $optionKey     klucz opcji formularza
     *
     * @return array
     */
    protected function _renderConfigOptionN($tinyKeyPrefix, $optionKey)
    {
        $options = [];
        if (null === $optionValue = $this->getOption($optionKey)) {
            return [];
        }

        if (!is_array($optionValue)) {
            $optionValue = [$optionValue];
        }

        foreach ($optionValue as $index => $value) {
            $options[$tinyKeyPrefix . ($index + 1)] = $value;
        }

        return $options;
    }

    /**
     * Bazowa konfiguracja dla wszystkich edytorów
     */
    protected function _baseConfig(View $view)
    {
        if ($this->getPlugins() === null) {
            $this->setPlugins(self::PLUGINS);
        }
        if ($this->getFontFormats() === null) {
            $this->setFontFormats(implode(';', self::FONT_FORMATS));
        }
        if ($this->getFontSizeFormats() === null) {
            $this->setFontSizeFormats(self::FONT_SIZES);
        }
        $this->_common = [
            "autoresize_min_height" => $this->getHeight() ?? 300,
            "convert_urls"          => false,
            "entity_encoding"       => 'raw',
            "relative_urls"         => false,
            "paste_data_images"     => false,
            "plugin_preview_height" => self::PLUGIN_PREVIEW_HEIGHT,
            "plugin_preview_width"  => self::PLUGIN_PREVIEW_WIDTH,
            "image_class_list"      => self::IMAGE_CLASS_LIST,
        ];
    }

    /**
     * Konfiguracja dla trybu Simple
     */
    protected function _modeSimple()
    {
        if ($this->getToolbars() === null) {
            $this->setToolbars(self::TOOLBARS['simple']);
        }
        if ($this->getContextMenu() === null) {
            $this->setContextMenu(self::CONTEXT_MENU['simple']);
        }
        if ($this->getResize() === null) {
            $this->setResize(false);
        }
        if ($this->getMenubar() === null) {
            $this->setMenubar(false);
        }
    }

    /**
     * Konfiguracja dla trybu Technical
     */
    protected function _modeAdvanced()
    {
        if ($this->getToolbars() === null) {
            $this->setToolbars(self::TOOLBARS['advanced']);
        }
        if ($this->getContextMenu() === null) {
            $this->setContextMenu(self::CONTEXT_MENU['advanced']);
        }
    }
}
