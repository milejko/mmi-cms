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
abstract class UploaderElementAbstract extends ElementAbstract
{

    //przedrostek tymczasowego obiektu plików
    const TEMP_OBJECT_PREFIX = 'tmp-';
    const PLACEHOLDER_NAME = '.placeholder';
    const FILES_MOVED_OPTION_PREFIX = 'move-files-handled-';

    /**
     * Ustawia form macierzysty
     * @param Form $form
     * @return self
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
        $response->redirectToUrl($this->view->url($request->toArray() + ['uploaderId' => mt_rand(1000000, 9999999)]));
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
        if (null !== $placeholder = CmsFileQuery::byObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId())
            ->whereName()->equals(self::PLACEHOLDER_NAME)
            ->findFirst()) {
            $placeholder->delete();
        }
        //przenoszenie plikow z tymczasowego "worka" do docelowego
        File::move(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), $this->getObject(), $this->getObjectId());
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
                ->byObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId())
                ->count()) {
            return true;
        }
        //tworzymy pliki tymczasowe - kopie oryginałów
        File::link($this->getObject(), $this->getObjectId(), self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId());
        $placeholder = new CmsFileRecord();
        $placeholder->name = self::PLACEHOLDER_NAME;
        $placeholder->object = self::TEMP_OBJECT_PREFIX . $this->getObject();
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
}
