<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Cms\Model\File;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Mmi\App\App;
use Mmi\Form\Form;
use Mmi\Http\Request;
use Mmi\Validator\StringLength;

/**
 * Element wielokrotny upload
 */
class MultiUpload extends MultiField implements UploaderElementInterface
{
    public const FILE_ELEMENT_NAME = 'file';
    private const MULTIUPLOAD_CSS_URL = '/resource/cmsAdmin/css/multiupload.css';
    private const MULTIUPLOAD_JS_URL = '/resource/cmsAdmin/js/multiupload.js';
    private const ICONS_URL = '/resource/cmsAdmin/images/upload/';
    private const UPLOAD_URL = '/cmsAdmin/upload/multiupload';
    private const THUMB_URL = '/cmsAdmin/upload/multithumbnail';
    private const CURRENT_URL = '/cmsAdmin/upload/current';

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
            ->addElement(
                (new Hidden(self::FILE_ELEMENT_NAME))
                    ->addValidator(
                        new StringLength(
                            [
                                'min' => 32,
                                'max' => 40,
                                'message' => 'validator.noFile.message',
                            ]
                        )
                    )
                    ->setRequired()
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
            $this->_createTempFiles();
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
                <input type="file" multiple="multiple" id="' . $this->getId() . '-add" class="upload-add" 
                    data-template="' . $this->getDeclaredName() . '" 
                    data-thumb-url="' . self::THUMB_URL . '" 
                    data-icons-url="' . self::ICONS_URL . '" 
                    data-current-url="' . self::CURRENT_URL . '" 
                    data-upload-url="' . self::UPLOAD_URL . '"
                    data-object="' . $this->getUploader() . '"
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
     * @param string $index
     *
     * @return string
     */
    protected function renderListElement(?array $itemValues = null, string $index = '**'): string
    {
        $html = '<li class="field-list-item border mb-3 p-3">
            <div class="icons">
                <a href="#" class="btn-toggle" role="button">
                    <i class="fa fa-angle-down fa-6"></i>
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
                $element->setChecked($element->getValue());
            }

            if (self::FILE_ELEMENT_NAME === $element->getBaseName()) {
                $element->setValue($itemValues[$element->getBaseName()] ?? '{{cmsFileName}}');
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
     * Po zapisie rekordu
     */
    public function onRecordSaved()
    {
        //brak zdefiniowanego objectId
        if (!$this->getObjectId()) {
            //pobranie id z rekordu
            $this->setObjectId($this->_form->getRecord()->id);
        }
        parent::onRecordSaved();
    }

    /**
     * Zapis formularza przenosi pliki
     */
    public function onFormSaved()
    {
        //pliki już obsłużone (inny uploader z tym samym prefixem)
        if ($this->_form->getOption(self::FILES_MOVED_OPTION_PREFIX . $this->getObject())) {
            return parent::onFormSaved();
        }
        //ustawianie flagi na formie dla innych uploaderów
        $this->_form->setOption(self::FILES_MOVED_OPTION_PREFIX . $this->getObject(), true);
        //usuwanie z docelowego "worka"
        File::deleteByObject($this->getObject(), $this->getObjectId());
        //usuwanie niepotrzebnych plikow
        File::deleteByObject($this->getUploader(), $this->getUploaderId(), $this->getFileNames());
        //przenoszenie plikow z tymczasowego "worka" do docelowego
        File::move($this->getUploader(), $this->getUploaderId(), $this->getObject(), $this->getObjectId());
        //usuwanie placeholdera
        if (null !== $placeholder = CmsFileQuery::byObject($this->getUploader(), $this->getUploaderId())
                ->whereName()->equals(self::PLACEHOLDER_NAME)
                ->findFirst()) {
            $placeholder->delete();
        }
        return parent::onFormSaved();
    }

    /**
     * Utorzenie kopii plików dla tego uploadera
     * @return boolean
     */
    protected function _createTempFiles()
    {
        //jeśli już są pliki tymczasowe, to wychodzimy
        if ((new CmsFileQuery())
            ->byObject($this->getUploader(), $this->getUploaderId())
            ->count()) {
            return true;
        }
        //tworzymy pliki tymczasowe - kopie oryginałów
        File::link($this->getObject(), $this->getObjectId(), $this->getUploader(), $this->getUploaderId());
        $placeholder = new CmsFileRecord();
        $placeholder->name = self::PLACEHOLDER_NAME;
        $placeholder->object = $this->getUploader();
        $placeholder->objectId = $this->getUploaderId();
        $placeholder->save();
        return true;
    }

    /**
     * Generuje automatyczny obiekt dla plików na podstawie nazwy klasy formularza
     * @param string $name
     * @return string
     */
    protected function _getFileObjectByClassName($name)
    {
        $parts = \explode('\\', strtolower($name));
        return substr(end($parts), 0, -6);
    }

    /**
     * @return string[]
     */
    private function getFileNames(): array
    {
        $names = [];

        foreach ($this->getValue() ?? [] as $item) {
            if (isset($item[self::FILE_ELEMENT_NAME])) {
                $names[] = $item[self::FILE_ELEMENT_NAME];
            }
        }

        return $names;
    }

    /**
     * @return string
     */
    private function getUploader(): string
    {
        return self::TEMP_OBJECT_PREFIX . $this->getObject();
    }
}
