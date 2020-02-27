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

    //szablon rozpoczynający formularz
    CONST TEMPLATE_START = 'cmsAdmin/form/start';

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
        }
        parent::__construct($record, $options);
    }

    /**
     * Po zapisie
     * @return boolean
     */
    public function afterSave()
    {
        //po uploadzie
        $this->afterUpload();
        return true;
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
            //dla każdego elementu Plupload / TinyMce
            if (($element instanceof \Cms\Form\Element\Plupload || $element instanceof \Cms\Form\Element\TinyMce) && $element->getUploaderObject()) {
                //zastępowanie plików
                \Cms\Model\File::deleteByObject($element->getObject(), $objectId);
                \Cms\Model\File::move('tmp-' . $element->getObject(), $element->getUploaderId(), $element->getObject(), $objectId);
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
