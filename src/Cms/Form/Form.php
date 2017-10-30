<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

/**
 * Formularz CMS
 */
abstract class Form extends \Mmi\Form\Form
{

    /**
     * Nazwa obiektu do przypięcia plików
     * @var string
     */
    protected $_fileObjectName;

    CONST EDITING_RECORD_OPTION_KEY = 'editingExistingRecord';

    /**
     * Konstruktor
     * @param \Mmi\Orm\Record $record obiekt recordu
     * @param array $options opcje
     * @param string $className nazwa klasy
     */
    public function __construct(\Mmi\Orm\Record $record = null, array $options = [])
    {
        //kalkulacja nazwy plików dla active record
        if ($record) {
            $this->_fileObjectName = $this->_classToFileObject(get_class($record));
            //informacja o tym, że rekord jest edytowany (dla uploadera)
            $this->setOption(self::EDITING_RECORD_OPTION_KEY, ($record->getPk() > 0));
        }
        parent::__construct($record, $options);
    }

    /**
     * Wywołuje walidację i zapis rekordu powiązanego z formularzem.
     * @return bool
     */
    public function save()
    {
        $result = parent::save();
        if ($result) {
            if ($this->hasRecord()) {
                $this->_appendFiles($this->_record->getPk(), $this->getFiles());
            }
            $this->afterUpload();
        }
        return $this->isSaved();
    }

    /**
     * Wywołuje metodę po uploadzie
     */
    public function afterUpload()
    {
        //domyślnie objectId NULL dla elementów bez rekordu
        $objectId = null;
        //jeśli mamy rekord, to bierzemy z niego objectId
        if ($this->hasRecord() && $this->getRecord()->getPk()) {
            $objectId = $this->getRecord()->getPk();
        }
        //dla każdego elementu Plupload
        foreach ($this->getElements() as $element) {
            if (!$element instanceof \Cms\Form\Element\Plupload || !$element->getObject()) {
                continue;
            }
            //usunięcie poprzednich plików - oryginalnych
            \Cms\Model\File::deleteByObject($element->getObject(), $objectId);
            //przenoszenie z uploadera plików ze zmienionym object
            \Cms\Model\File::move('tmp-' . $element->getObject(), $element->getUploaderId(), $element->getObject(), $objectId);
        }
    }

    /**
     * Zwraca nazwę obiektu do przypięcia plików
     * @return string
     */
    public function getFileObjectName()
    {
        return $this->_fileObjectName;
    }

    /**
     * Ustawia nazwę obiektu do przypięcia plików
     * @param string $name nazwa
     */
    public function setFileObjectName($name)
    {
        $this->_fileObjectName = $name;
    }

    /**
     * Dołaczenie plików do obiektu
     * @param mixed $id
     * @param array $files tabela plików
     */
    protected function _appendFiles($id, $files)
    {
        try {
            foreach ($files as $fileSet) {
                \Cms\Model\File::appendFiles($this->_fileObjectName, $id, $fileSet);
            }
            //rekord edytowany - nie łączymy uploaderów z tymczasowym uploadem
            if ($this->getOption(self::EDITING_RECORD_OPTION_KEY)) {
                return;
            }
            //przenoszenie z uploadera
            \Cms\Model\File::move('tmp-' . $this->_fileObjectName, \Mmi\Session\Session::getNumericId(), $this->_fileObjectName, $id);
        } catch (\Exception $e) {
            \Mmi\App\FrontController::getInstance()->getLogger()->warning($e->getMessage());
        }
    }

    /**
     * Import plików z pól formularza
     * Zwraca tabelę danych plików
     * @return array
     */
    public function getFiles()
    {
        $files = [];
        //import z elementów File
        foreach ($this->getElements() as $element) {
            if (!($element instanceof \Mmi\Form\Element\File)) {
                continue;
            }
            /* @var $element \Mmi\Form\Element\File */
            if (!$element->isUploaded()) {
                continue;
            }
            $files[$element->getName()] = $element->getFiles();
        }
        return $files;
    }

    /**
     * Zwraca nazwę plików powiązanych z danym formularzem (na podstawie klasy rekordu / modelu)
     * @param string $name
     * @return string
     */
    protected function _classToFileObject($name)
    {
        $parts = \explode('\\', strtolower($name));
        return substr(end($parts), 0, -6);
    }

}
