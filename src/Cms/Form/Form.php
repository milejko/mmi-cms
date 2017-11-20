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
        //aktualizacja wartości pól w rekordzie
        $this->_updateRecordDataBeforeSave();
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
     * Aktualizuje wartości pól rekordu formularza przed zapisem
     * @return null
     */
    protected function _updateRecordDataBeforeSave()
    {
        //jeśli formularz nie ma rekordu
        if (!$this->hasRecord()) {
            return;
        }
        //dla każdego elementu formularza
        foreach ($this->getElements() as $element) {
            //pomijamy inne elementy niż TinyMce
            if (!$element instanceof \Cms\Form\Element\TinyMce || !$element->getUploaderObject()) {
                continue;
            }
            //dla każdego elementu TinyMce
            $value = $element->getValue();
            foreach (\Cms\Orm\CmsFileQuery::byObjectJoinedOriginal('tmp-' . $element->getUploaderObject(), $element->getUploaderId())
                ->find() as $file) {
                if (!$file->getJoined('original_file') || !$file->getJoined('original_file')->name) {
                    continue;
                }
                $oName = $file->getJoined('original_file')->name;
                $tName = $file->name;
                $value = preg_replace('@/data/'.$tName[0].'/'.$tName[1].'/'.$tName[2].'/'.$tName[3].'/(scalecrop|scalex|scaley|default)/([0-9x]{0,10})/'.$tName.'@',
                    '/data/'.$oName[0].'/'.$oName[1].'/'.$oName[2].'/'.$oName[3].'/$1/$2/'.$oName, $value);
            }
            $element->setValue($value);
            $this->_record->setFromArray([$element->getName() => $value]);
        }
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
        //lista kluczy Cms File dla elementów TinyMce
        $tinyObjects = [];
        //dla każdego elementu formularza
        foreach ($this->getElements() as $element) {
            //dla każdego elementu Plupload
            if ($element instanceof \Cms\Form\Element\Plupload && $element->getObject()) {
                //łączenie tymczasowych plików z uploadera (kopii) z oryginałami
                (new \Cms\Model\FileMerge('tmp-' . $element->getObject(), $element->getUploaderId(), $element->getObject(), $objectId))->merge();
                continue;
            }
            //dla każdego elementu TinyMce
            if ($element instanceof \Cms\Form\Element\TinyMce && $element->getUploaderObject()) {
                //jeśli już obsłużono klucz Cms File
                if (in_array($element->getUploaderObject(), $tinyObjects)) {
                    continue;
                }
                array_push($tinyObjects, $element->getUploaderObject());
                //łączenie tymczasowych plików z uploadu przez TinyMce (kopii) z oryginałami
                (new \Cms\Model\FileMerge('tmp-' . $element->getUploaderObject(), $element->getUploaderId(), $element->getUploaderObject(), $objectId))->merge();
                continue;
            }
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
