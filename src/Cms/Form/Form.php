<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

use Cms\Form\Element;

/**
 * Formularz CMS
 */
abstract class Form extends \Mmi\Form\Form {

	/**
	 * Nazwa obiektu do przypięcia plików
	 * @var string
	 */
	protected $_fileObjectName;

	/**
	 * Konstruktor
	 * @param \Mmi\Orm\Record $record obiekt recordu
	 * @param array $options opcje
	 * @param string $className nazwa klasy
	 */
	public function __construct(\Mmi\Orm\Record $record = null) {
		//kalkulacja nazwy plików dla active record
		if ($record) {
			$this->_fileObjectName = $this->_classToFileObject(get_class($record));
		}
		parent::__construct($record);
	}

	/**
	 * Wywołuje walidację i zapis rekordu powiązanego z formularzem.
	 * @return bool
	 */
	public function save() {
		if ($this->hasRecord() && parent::save()) {
			$this->_appendFiles($this->_record->getPk(), $this->getFiles());
		}
		return $this->isSaved();
	}

	/**
	 * Zabezpieczenie spamowe
	 * @param string $name nazwa
	 * @return \Cms\Form\Element\Antirobot
	 */
	public function addElementAntirobot($name) {
		return $this->addElement(new Element\Antirobot($name));
	}

	/**
	 * Captcha
	 * @param string $name nazwa
	 * @return \Cms\Form\Element\Captcha
	 */
	public function addElementCaptcha($name) {
		return $this->addElement(new Element\Captcha($name));
	}

	/**
	 * Wybór koloru
	 * @param string $name nazwa
	 * @return \Cms\Form\Element\ColorPicker
	 */
	public function addElementColorPicker($name) {
		return $this->addElement(new Element\ColorPicker($name));
	}

	/**
	 * Date picker
	 * @param string $name nazwa
	 * @return \Cms\Form\Element\DatePicker
	 */
	public function addElementDatePicker($name) {
		return $this->addElement(new Element\DatePicker($name));
	}

	/**
	 * Date-time picker
	 * @param string $name nazwa
	 * @return \Cms\Form\Element\DateTimePicker
	 */
	public function addElementDateTimePicker($name) {
		return $this->addElement(new Element\DateTimePicker($name));
	}

	/**
	 * TinyMce
	 * @param string $name nazwa
	 * @return \Cms\Form\Element\TinyMce
	 */
	public function addElementTinyMce($name) {
		return $this->addElement(new Element\TinyMce($name));
	}

	/**
	 * Uploader
	 * @param string $name nazwa
	 * @return \Cms\Form\Element\Uploader
	 */
	public function addElementUploader($name) {
		return $this->addElement(new Element\Uploader($name));
	}

	/**
	 * Zwraca nazwę obiektu do przypięcia plików
	 * @return string
	 */
	public function getFileObjectName() {
		return $this->_fileObjectName;
	}

	/**
	 * Ustawia nazwę obiektu do przypięcia plików
	 * @param string $name nazwa
	 */
	public function setFileObjectName($name) {
		$this->_fileObjectName = $name;
	}

	/**
	 * Dołaczenie plików do obiektu
	 * @param mixed $id
	 * @param array $files tabela plików
	 */
	protected function _appendFiles($id, $files) {
		try {
			foreach ($files as $fileSet) {
				\Cms\Model\File::appendFiles($this->_fileObjectName, $id, $fileSet);
			}
			//przenoszenie z uploadera
			\Cms\Model\File::move('tmp-' . $this->_fileObjectName, \Mmi\Session\Session::getNumericId(), $this->_fileObjectName, $id);
		} catch (\Exception $e) {
			\Mmi\App\FrontController::getInstance()->getLogger()->addWarning($e->getMessage());
		}
	}

	/**
	 * Import plików z pól formularza
	 * Zwraca tabelę danych plików
	 * @return array
	 */
	public function getFiles() {
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
	protected function _classToFileObject($name) {
		$name = explode('\\', $name);
		$fileObject = '';
		foreach ($name as $part) {
			$part = strtolower($part);
			if (isset($first) && $part == $first || $part == 'orm' || $part == 'record') {
				continue;
			}
			$first = $part;
			$fileObject .= $part;
		}
		return $fileObject;
	}

}
