<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Widget;

class Picture extends \Cms\Form\Component {

	public function init() {

		$this->addElementFile('cmswidgetpicture')
			->setLabel('Dodaj zdjęcie')
			->setRequired();

		$this->addElementSubmit('submit')
			->setLabel('Zapisz zdjęcie');
	}

	public function validator() {
		$files = $this->getFiles()[$this->getFileObjectName()];
		if (empty($files)) {
			$this->getElement('cmswidgetpicture')->addError('Wskaż plik zdjęcia');
			return false;
		}
		return true;
	}

	protected function _appendFiles($id, $files) {
		if (empty($files)) {
			return;
		}
		$object = 'cmswidgetpicture';
		//zastapienie obecnego pliku
		\Cms\Model\File::deleteByObject($object, $id);
		\Cms\Model\File::appendFiles($object, $id, $files);
	}

}
