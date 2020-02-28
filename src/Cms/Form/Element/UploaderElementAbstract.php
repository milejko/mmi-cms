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
use Mmi\App\FrontController;
use Mmi\Form\Element\ElementAbstract;
use Mmi\Form\Form;

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

    /**
     * Ustawia form macierzysty
     * @param Form $form
     * @return self
     */
    public function setForm(Form $form)
    {
        //parent
        parent::setForm($form);
        //obiekt niezdefiniowany
        if (!$this->getObject()) {
            //ustawianie obiektu
            $this->setObject($form->getFileObjectName());
        }
        //ustawienie ID
        if (!$this->getObjectId() && $form->hasRecord()) {
            //pobranie id z rekordu
            $this->setObjectId($form->getRecord()->id);
        }
        //instancja front controllera
        $frontController = FrontController::getInstance();
        //uploaderId znajduje się w requescie
        if ($frontController->getRequest()->uploaderId) {
            //ustawianie id uploadera
            $this->setUploaderId($frontController->getRequest()->uploaderId);
            //tworzenie plików tymczasowych
            $this->_createTempFiles();
            return $this;
        }
        //przekierowanie na url zawierający nowowygenerowany uploaderId
        $frontController->getResponse()->redirectToUrl($frontController->getView()->url($frontController->getRequest()->toArray() + ['uploaderId' => mt_rand(1000000, 9999999)]));
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
        //usuwanie z docelowego "worka"
        File::deleteByObject($this->getObject(), $this->getObjectId());
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
        return true;
    }

}