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
use Mmi\Http\Request;
use Mmi\Http\Response;
use Cms\Model\File as FileModel;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Mmi\Form\Element\ElementAbstract;
use Mmi\Http\RequestFiles;
use Mmi\Http\RequestPost;
use Mmi\Mvc\View;
use Mmi\Session\SessionSpace;

/**
 * Element TinyMce specyficzny dla generatora
 * Gettery
 * @method string getObject() pobiera obiekt
 * @method int getObjectId() pobiera identyfikator obiektu
 * @method int getUploaderId() pobiera identyfikator uploadera
 *
 * Settery
 * @method self setObject($object) ustawia obiekt
 * @method self setObjectId($id) ustawia identyfikator obiektu
 * @method self setDeleteCheckboxName($name) ustawia nazwę checkboxa
 * @method self setUploaderId($id) ustawia id uploadera
 */
class Image extends ElementAbstract implements UploaderElementInterface
{
    //szablon początku pola
    public const TEMPLATE_BEGIN = 'cmsAdmin/form/element/element-abstract/begin';
    //szablon opisu
    public const TEMPLATE_DESCRIPTION = 'cmsAdmin/form/element/element-abstract/description';
    //szablon pola
    public const TEMPLATE_FIELD = 'cmsAdmin/form/element/image';
    //szablon końca pola
    public const TEMPLATE_END = 'cmsAdmin/form/element/element-abstract/end';
    //szablon błędów
    public const TEMPLATE_ERRORS = 'cmsAdmin/form/element/element-abstract/errors';
    //szablon etykiety
    public const TEMPLATE_LABEL = 'cmsAdmin/form/element/element-abstract/label';

    //klucz z losowym id uploadera
    public const UPLOADER_ID_KEY = 'uploaderId';
    public const SESSION_NAMESPACE_PREFIX = 'imageuploader-';
    //suffixy dodatkowych pól hidden
    public const DELETE_FIELD_SUFFIX = '-delete';

    /**
     * Załadowany plik
     * @var CmsFileRecord
     */
    protected $_uploadedFile;

    /**
     * Przestrzeń w sesji do użytku uploadera
     * @var SessionSpace
     */
    protected $_sessionSpace;

    /**
     * Metoda wywoływana po dodaniu pola do formularza
     * @param \Mmi\Form\Form $form
     * @return $this|ElementAbstract|void
     * @throws \Mmi\App\KernelException
     */
    public function setForm(\Mmi\Form\Form $form)
    {
        //parent
        parent::setForm($form);
        $this->setIgnore();
        //nazwa pola
        $this->setDeleteCheckboxName($form->getBaseName() . '[' . $this->getBasename() . self::DELETE_FIELD_SUFFIX . ']');
        //obiekt niezdefiniowany
        if (!$this->getObject()) {
            //ustawianie obiektu
            $this->setObject($form->getFileObjectName());
        }
        //ustawia id obiektu na podstawie rekordu z formularza (o ile istnieje)
        if (!$form->getRecord()) {
            return $this;
        }
        //ustawienie id
        $this->setObjectId($form->getRecord()->id);
        //instancja front controllera
        $request = App::$di->get(Request::class);
        $response = App::$di->get(Response::class);
        //uploaderId znajduje się w requescie
        if (!$request->uploaderId) {
            //przekierowanie na url zawierający nowowygenerowany uploaderId
            return $response->redirectToUrl(App::$di->get(View::class)->url($request->toArray() + [self::UPLOADER_ID_KEY => mt_rand(1000000, 9999999)]));
        }
        //ustawianie uploaderID
        $this->setUploaderId($request->uploaderId);
        //tworzenie przestrzeni sesyjnej
        $this->_sessionSpace = new SessionSpace(self::SESSION_NAMESPACE_PREFIX . $this->getObject() . $this->getUploaderId());
        //sprawdzenie czy pliki tymczasowe są już utworzone
        if (!$this->_sessionSpace->tempFilesCreated && $this->getObjectId()) {
            //przygotowanie plikow tymczasowych
            FileModel::link($this->getObject(), $this->getObjectId(), self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId());
            $this->_sessionSpace->tempFilesCreated = true;
        }
        //obsługa POST
        $this->_handlePost($form, $request->getPost(), $request->getFiles());
        //przypisanie zuploadowanego pliku (worek tymczasowy)
        $this->_uploadedFile = CmsFileQuery::byObjectAndClass(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), 'image')->findFirst();
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
        FileModel::deleteByObject($this->getObject(), $this->getObjectId());
        //przenoszenie plikow z tymczasowego "worka" do docelowego
        FileModel::move(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), $this->getObject(), $this->getObjectId());
        //czyszczenie przestrzeni sesyjnej
        $this->_sessionSpace->unsetAll();
        return parent::onRecordSaved();
    }

    /**
     * Pobiera zuploadowany plik
     * @return CmsFileRecord
     */
    public function getUploadedFile()
    {
        return $this->_uploadedFile;
    }

    /**
     * Obsługa uploadu pliku i checkboxa do kasowania
     * @param $form
     * @param RequestPost $post
     * @param RequestFiles $files
     */
    protected function _handlePost($form, RequestPost $post, RequestFiles $files)
    {
        //brak danych dotyczących pliku
        if (!isset($post->{$form->getBaseName()})) {
            return;
        }
        //zaznaczony checkbox usuwania
        if (isset($post->{$form->getBaseName()}[$this->getBasename() . self::DELETE_FIELD_SUFFIX])) {
            //usuwanie istniejącego pliku
            FileModel::deleteByObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId());
            //koniec metody - plik miał być usunięty (checkbox zaznaczony), nawet jeśli podany
            return;
        }
        $fileArray = $files->getAsArray();
        //brak pliku
        if (!isset($fileArray[$form->getBaseName()]) || !isset($fileArray[$form->getBaseName()][$this->getBasename()][0])) {
            return;
        }
        //usuwanie istniejącego pliku
        FileModel::deleteByObject(self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId());
        //zapis pliku
        FileModel::appendFile($fileArray[$form->getBaseName()][$this->getBasename()][0], self::TEMP_OBJECT_PREFIX . $this->getObject(), $this->getUploaderId(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }
}
