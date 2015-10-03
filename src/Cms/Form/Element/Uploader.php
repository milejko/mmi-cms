<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element uploader
 */
class Uploader extends \Mmi\Form\Element\File {

	/**
	 * Dodaje dozwolony typ pliku
	 * @param string $type
	 * @return \Cms\Form\Element\Uploader
	 */
	public function addAllowedType($type) {
		$types = $this->getOption('types');
		//brak typów - tworzenie
		if (null === $types) {
			$this->setOption('types', [$type => $type]);
		}
		$types[$type] = $type;
		return $this->setOption('types', $types);
	}

	/**
	 * Ustawia sciezke do opcjonalnego pliku JS w ramce
	 * @param string $path
	 * @return \Cms\Form\Element\Uploader
	 */
	public function addJsFile($path) {
		return $this->setOption('js', $path);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$object = 'library';
		$objectId = null;
		if ($this->_form->hasRecord()) {
			$object = $this->_form->getFileObjectName();
			$objectId = $this->_form->getRecord()->getPk();
		}
		if (!$objectId) {
			$object = 'tmp-' . $object;
			$objectId = \Mmi\Session\Session::getNumericId();
		}
		return '<iframe frameborder="0" src="' . \Mmi\App\FrontController::getInstance()->getView()->url([
				'module' => 'cms',
				'controller' => 'file',
				'action' => 'uploader',
				'class' => str_replace('\\', '', get_class($this->_form)),
				'object' => $object,
				'objectId' => $objectId,
				'types' => $this->getOption('types'),
				'js' => $this->getOption('js')
		]) . '"
			style="border-style: none;
			border: none;
			border-width: initial;
			border-color: initial;
			border-image: initial;
			padding: 0;
			margin: 0;
			overflow-x: hidden;
			overflow-y: auto;
			height: 180px;
			width: 100%;"></iframe>';
	}

}
