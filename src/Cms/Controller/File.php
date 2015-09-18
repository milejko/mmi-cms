<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

/**
 * Kontroler plików
 */
class File extends \Mmi\Controller\Action {
	
	/**
	 * Funkcja dla użytkownika ładowana na końcu konstruktora
	 */
	public function init() {
		\Mmi\Controller\Front::getInstance()->getResponse()->setHeader('X-UA-Compatible', 'IE=EmulateIE10', true);
	}

	/**
	 * Lista obrazów json (na potrzeby tinymce)
	 * @return string
	 */
	public function listAction() {
		\Mmi\Controller\Front::getInstance()->getResponse()->setHeader('Content-type', 'application/json');
		if (!$this->object || !$this->objectId || !$this->hash || !$this->t) {
			return '';
		}
		if ($this->hash != md5(\Mmi\Session::getId() . '+' . $this->t . '+' . $this->objectId)) {
			return '';
		}
		$files = [];
		foreach (\Cms\Orm\File\Query::imagesByObject($this->object, $this->objectId)->find() as $file) {
			$files[] = ['title' => $file->original, 'value' => $file->getUrl('scalex', '990')];
		}
		return json_encode($files);
	}

	/**
	 * Rendering uploadera
	 * @return string
	 */
	public function uploadAction() {
		$files = $this->getFiles()->toArray();
		//brak plików lub klasy
		$error = null;
		if (empty($files) || !isset($files['file']) || empty($files['file']) || !$this->class) {
			$error = 'Błąd: nie przesłano pliku';
		}
		//nieprawidłowy hash zabezpieczający
		if ($this->hash != md5($this->t . '+' . \Mmi\Session::getId() . '+' . $this->class)) {
			$error = 'Błąd: nieprawidłowy hash';
		}
		//jeśli nie było błędu
		if ($error === null) {
			//dołączanie plików
			if (!\Cms\Model\File::appendFiles($this->object, $this->objectId, $files, $this->types ? $this->types : [])) {
				$error = 'Nieprawidłowy typ pliku';
			}
		}
		//rendering widgeta uploadera
		return $this->view->widget('cms', 'file', 'uploader', [
				'object' => $this->object,
				'objectId' => $this->objectId,
				'types' => $this->types,
				'error' => $error
		]);
	}

	/**
	 * Widget uploadera
	 */
	public function uploaderAction() {
		//parametry dla ajaxa
		$this->view->ajaxParams = [
			'module' => 'cms',
			'controller' => 'file',
			'action' => 'upload',
			'class' => $this->class,
			'object' => $this->object,
			'objectId' => $this->objectId,
			'types' => $this->types,
			'js' => $this->js,
			//znacznik czasu
			't' => $t = round(microtime(true)),
			//hash zabezpieczający oparty o znacznik czasu
			'hash' => md5($t . '+' . \Mmi\Session::getId() . '+' . $this->class)
		];
		//blad
		$this->view->error = $this->error;
		//lista obrazów
		$this->view->images = \Cms\Orm\File\Query::imagesByObject($this->object, $this->objectId)
			->orderAscDateAdd()
			->find();
		//lista pozostałych plików
		$this->view->files = \Cms\Orm\File\Query::notImagesByObject($this->object, $this->objectId)
			->orderAscDateAdd()
			->find();
	}

}
