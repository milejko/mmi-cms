<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2020 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

use Cms\Model\File;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Mmi\App\App;
use Mmi\Form\Element\ElementAbstract;
use Mmi\Form\Form;
use Mmi\Http\Request;
use Mmi\Http\Response;

/**
 * Abstrakcyjna klasa uploadera
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
abstract class UploaderElementAbstract extends ElementAbstract implements UploaderElementInterface
{
    /**
     * Ustawia form macierzysty
     * @param Form $form
     * @return self
     */
    public function setForm(Form $form)
    {
        parent::setForm($form);
        $this->setIgnore();
        if (!$this->getObject() && $form->hasRecord()) {
            $this->setObject($this->_getFileObjectByClassName(get_class($form->getRecord())));
        }
        if (!$this->getObjectId() && $form->hasRecord()) {
            $this->setObjectId($form->getRecord()->id);
        }
        //instancja front controllera
        $request = App::$di->get(Request::class);
        $response = App::$di->get(Response::class);
        //uploaderId znajduje się w requescie
        if (!$request->uploaderId) {
            $response->redirectToUrl($this->view->url($request->toArray() + [self::REQUEST_UPLOADER_ID => mt_rand(1000000, 9999999)]));
        }
        //ustawianie id uploadera
        $this->setUploaderId($request->uploaderId);
        return $this;
    }

    public function fetchField()
    {
        //tworzenie plików tymczasowych
        $this->_createTempFiles();
        return parent::fetchField();
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
        //usuwanie placeholdera
        CmsFileQuery::byObject($this->getTemporaryObject(), $this->getUploaderId())
            ->whereName()->equals(self::PLACEHOLDER_NAME)
            ->delete();
        //przenoszenie plikow z tymczasowego "worka" do docelowego
        File::move($this->getTemporaryObject(), $this->getUploaderId(), $this->getObject(), $this->getObjectId());
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
                ->byObject($this->getTemporaryObject(), $this->getUploaderId())
                ->count()) {
            return true;
        }
        //tworzymy pliki tymczasowe - kopie oryginałów
        File::link($this->getObject(), $this->getObjectId(), $this->getTemporaryObject(), $this->getUploaderId());
        $placeholder = new CmsFileRecord();
        $placeholder->name = $placeholder->original = self::PLACEHOLDER_NAME;
        $placeholder->object = $this->getTemporaryObject();
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
     * @return string
     */
    private function getTemporaryObject(): string
    {
        return self::TEMP_OBJECT_PREFIX . $this->getObject();
    }
}
