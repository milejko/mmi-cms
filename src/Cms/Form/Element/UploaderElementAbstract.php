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
use DI\DependencyException;
use DI\NotFoundException;
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
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function setForm(Form $form)
    {
        //parent
        parent::setForm($form);
        //no value
        $this->setIgnore();
        //obiekt niezdefiniowany
        if (!$this->getObject() && $form->hasRecord()) {
            //ustawianie obiektu
            $this->setObject($this->_getFileObjectByClassName(get_class($form->getRecord())));
        }
        //ustawienie ID
        if (!$this->getObjectId() && $form->hasRecord()) {
            //pobranie id z rekordu
            $this->setObjectId($form->getRecord()->id);
        }
        //instancja front controllera
        $request = App::$di->get(Request::class);
        $response = App::$di->get(Response::class);
        //uploaderId znajduje się w requescie
        if ($request->uploaderId) {
            //ustawianie id uploadera
            $this->setUploaderId($request->uploaderId);
            //tworzenie plików tymczasowych
            $this->_createTempFiles();
            return $this;
        }
        //przekierowanie na url zawierający nowowygenerowany uploaderId
        $response->redirectToUrl($this->view->url($request->toArray() + [self::UPLOADER_ID_KEY => mt_rand(1000000, 9999999)]));
        return $this;
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
        if (null !== $placeholder = CmsFileQuery::byObject($this->getUploader(), $this->getUploaderId())
            ->whereName()->equals(self::PLACEHOLDER_NAME)
            ->findFirst()) {
            $placeholder->delete();
        }
        //przenoszenie plikow z tymczasowego "worka" do docelowego
        File::move($this->getUploader(), $this->getUploaderId(), $this->getObject(), $this->getObjectId());
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
     * @return string
     */
    protected function getUploader(): string
    {
        return self::TEMP_OBJECT_PREFIX . $this->getObject();
    }
}
