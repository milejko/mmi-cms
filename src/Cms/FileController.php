<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler plików
 */
class FileController extends \Mmi\Mvc\Controller {
	
	/**
	 * Funkcja dla użytkownika ładowana na końcu konstruktora
	 */
	public function init() {
		\Mmi\App\FrontController::getInstance()->getResponse()->setHeader('X-UA-Compatible', 'IE=EmulateIE10', true);
	}

	/**
	 * Lista obrazów json (na potrzeby tinymce)
	 * @return string
	 */
	public function listAction() {
		\Mmi\App\FrontController::getInstance()->getResponse()->setHeader('Content-type', 'application/json');
		if (!$this->object || !$this->objectId || !$this->hash || !$this->t) {
			return '';
		}
		if ($this->hash != md5(\Mmi\Session\Session::getId() . '+' . $this->t . '+' . $this->objectId)) {
			return '';
		}
		$files = [];
		foreach (\Cms\Orm\CmsFileQuery::imagesByObject($this->object, $this->objectId)->find() as $file) {
			$files[] = ['title' => $file->original, 'value' => $file->getUrl('scalex', '1200', true)];
		}
		return json_encode($files);
	}
	
	/**
	 * Lista obrazów (na potrzeby tinymce)
	 * @return view layout
	 */
	public function listLayoutAction() {
		$this->view->setLayoutDisabled();
		if (!$this->object || !$this->objectId || !$this->hash || !$this->t) {
			return '';
		}
		if ($this->hash != md5(\Mmi\Session\Session::getId() . '+' . $this->t . '+' . $this->objectId)) {
			return '';
		}
		$files = [];
		$thumb = new \Cms\Mvc\ViewHelper\Thumb();
		foreach (\Cms\Orm\CmsFileQuery::byObjectAndClass($this->object, $this->objectId, $this->class)->find() as $file) {
			$files[] = [
				'id' => $file->id,
				'title' => $file->original, 
				'stream' => '/?module=file&controller=server&action=stream&fileId='.$file->id.'&t='.strtotime($file->dateModify), 
                                'full' => $thumb->thumb($file, 'default'),
				'thumb' => $file->getUrl('scaley', '60', false),
				'class' => $file->class,
				'mime' => $file->mimeType,
			];
		}
		
		//przekazanie danych
		$this->view->files = $files;
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
		if ($this->hash != md5($this->t . '+' . \Mmi\Session\Session::getId() . '+' . $this->class)) {
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
			'hash' => md5($t . '+' . \Mmi\Session\Session::getId() . '+' . $this->class)
		];
		//blad
		$this->view->error = $this->error;
		//lista obrazów
		$this->view->images = \Cms\Orm\CmsFileQuery::imagesByObject($this->object, $this->objectId)
			->orderAscDateAdd()
			->find();
		//lista pozostałych plików
		$this->view->files = \Cms\Orm\CmsFileQuery::notImagesByObject($this->object, $this->objectId)
			->orderAscDateAdd()
			->find();
	}

}
