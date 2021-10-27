<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Mmi\App\App;
use Mmi\Form\Element\ElementAbstract;
use Mmi\Form\Form;
use Mmi\Http\Request;
use Mmi\Validator\NotEmpty;

/**
 * Element wielokrotny upload
 */
class MultiUpload extends MultiField
{
    //pliki js i css
    private const MULTIUPLOAD_CSS_URL = '/resource/cmsAdmin/css/multiupload.css';
    private const MULTIUPLOAD_JS_URL  = '/resource/cmsAdmin/js/multiupload.js';
    private const ICONS_URL           = '/resource/cmsAdmin/images/upload/';
    private const UPLOAD_URL          = '/cmsAdmin/upload/multiupload';
    private const THUMB_URL           = '/cmsAdmin/upload/multithumbnail';
    private const CURRENT_URL         = '/cmsAdmin/upload/current';

    //przedrostek tymczasowego obiektu plików
    public const TEMP_OBJECT_PREFIX = 'tmp-';

    /**
     * Elementy formularza
     *
     * @var ElementAbstract[]
     */
    protected array $_elements = [];

    /**
     * Błędy elementów formularza
     *
     * @var array
     */
    protected array $_elementErrors = [];

    /**
     * Błędy zagnieżdzonych elementów formularza
     *
     * @var array
     */
    protected array $_elementNestedErrors = [];

    /**
     * Konstruktor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this
            ->addClass('multiupload')
            ->addElement(new Hidden('file'))
            ->addElement(
                (new Text('filename'))
                    ->setLabel('Nazwa pliku')
                    ->setRequired()
                    ->addValidator(new NotEmpty())
            );
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form): self
    {
        if (!$this->getObject() && $form->hasRecord()) {
            $this->setObject($this->_getFileObjectByClassName(get_class($form->getRecord())));
        }

        if (!$this->getObjectId() && $form->hasRecord()) {
            $this->setObjectId($form->getRecord()->id);
        }

        $request = App::$di->get(Request::class);
        if ($request->uploaderId) {
            $this->setUploaderId($request->uploaderId);
        }

        return parent::setForm($form);
    }

    /**
     * @return string
     */
    public function fetchField(): string
    {
        $this->addScriptsAndLinks();

        return '<div id="' . $this->getId() . '-list" class="' . $this->getClass() . '">
            <label for="' . $this->getId() . '-add" class="upload-add-label">
                <i class="icon fa fa-5 fa-cloud-upload"></i>
                Kliknij lub upuść pliki w tym obszarze
                <input type="file" id="' . $this->getId() . '-add" class="upload-add" 
                    data-template="' . $this->getDeclaredName() . '" 
                    data-thumb-url="' . self::THUMB_URL . '" 
                    data-icons-url="' . self::ICONS_URL . '" 
                    data-current-url="' . self::CURRENT_URL . '" 
                    data-upload-url="' . self::UPLOAD_URL . '"
                    data-object="' . self::TEMP_OBJECT_PREFIX . $this->getObject() . '"
                    data-object-id="' . $this->getUploaderId() . '"
                    data-file-id="' . $this->getId() . '"
                >                
                <div class="multiupload-progress-bar"><div class="progress"></div></div>
            </label>
            ' . $this->renderList() . '            
            </div>';
    }

    /**
     * Renderer pol formularza
     *
     * @param array|null $itemValues
     * @param string     $index
     *
     * @return string
     */
    protected function renderListElement(?array $itemValues = null, string $index = '**'): string
    {
        $html = '<li class="field-list-item border mb-3 p-3">
            <div class="icons">
                <a href="#" class="btn-toggle" role="button">
                    <i class="fa fa-angle-down fa-2"></i>
                </a>
            </div>
            <div class="thumb">
                <img src="/resource/cmsAdmin/images/loader.gif">
            </div>
        <section>';

        foreach ($this->getElements() as $element) {
            $element->setId($this->getId() . '-' . $index . '-' . $element->getBaseName());
            $element->setName($this->getName() . '[' . $index . '][' . $element->getBaseName() . ']');
            $element->setValue($itemValues[$element->getBaseName()] ?? null);
            $element->setErrors($this->_elementErrors[$index][$element->getBaseName()] ?? []);

            if ($element instanceof Checkbox) {
                $element->getValue() ? $element->setChecked() : $element->setChecked(false);
            }

            if ($element instanceof Hidden) {
                $element->setValue($itemValues[$element->getBaseName()] ?? '{{cmsFileId}}');
            }

            $html .= $element->__toString();
        }

        $html .= '</section>
            <div class="icons">
                <a href="#" class="sortable-handler" role="button">
                    <i class="fa fa-arrows fa-2"></i>
                </a>
                <a href="#" class="btn-active" role="button">
                    <i class="fa fa-eye fa-2"></i>
                </a>
                <a href="#" class="btn-remove" role="button">
                    <i class="fa fa-trash-o fa-2"></i>
                </a>
            </div>
        </li>';

        return trim(preg_replace('/\r|\n|\s\s+/', ' ', $html));
    }

    protected function addScriptsAndLinks(): void
    {
        parent::addScriptsAndLinks();

        $this->view->headLink()->appendStylesheet(self::MULTIUPLOAD_CSS_URL);
        $this->view->headScript()->appendFile(self::MULTIUPLOAD_JS_URL);
    }

    /**
     * @return string
     */
    protected function jsScript(): string
    {
        $listElement = addcslashes($this->renderListElement(), "'");
        $listType    = $this->getDeclaredName();

        return <<<html
            $(document).ready(function() {
                multifieldListItemTemplate['$listType'] = '$listElement';
            });
        html;
    }
}
