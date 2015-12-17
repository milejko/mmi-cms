<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler pobierania plików
 */
class UploadController extends Mvc\Controller {

	/**
	 * Odbieranie danych z plugina Plupload
	 */
	public function pluploadAction() {
		set_time_limit(5 * 60);
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		//obiekt handlera plupload
		$pluploadHandler = new Model\PluploadHandler(BASE_PATH . '/var/plupload/');
		//jeśli wystąpił błąd
		if (!$pluploadHandler->handle()) {
			return $this->_jsonError($pluploadHandler->getErrorCode(), $pluploadHandler->getErrorMessage());
		}
		return json_encode(['result' => 'OK', 'cmsFileId' => $pluploadHandler->getSavedCmsFileId()]);
	}
	
	/**
	 * Zwraca listę aktualnych plików przypiętych do obiektu formularza
	 */
	public function currentAction() {
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		//zwrot json'a z plikami
		return json_encode([
			'result' => 'OK',
			'files' => \Cms\Orm\CmsFileQuery::byObject($this->getPost()->object, $this->getPost()->objectId)
						->orderAscDateAdd()
						->find()
						->toArray()
		]);
	}
	
	/**
	 * Usuwa wybrany rekord pliku
	 */
	public function deleteAction() {
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		if (!$this->getPost()->cmsFileId) {
			return $this->_jsonError(178);
		}
		//szukamy rekordu pliku
		if (null !== $record = (new \Cms\Orm\CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
			//sprawdzenie zgodności z obiektem formularza
			if ($record->object === $this->getPost()->object && $record->objectId == $this->getPost()->objectId) {
				//usuwanie
				if ($record->delete()) {
					return json_encode(['result' => 'OK']);
				}
			}
		}
		return $this->_jsonError(178);
	}
	
	/**
	 * Zwraca minaturę wybranego rekord pliku
	 */
	public function thumbnailAction() {
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		if (!$this->getPost()->cmsFileId) {
			return $this->_jsonError(179);
		}
		//szukamy rekordu pliku
		if (null !== $record = (new \Cms\Orm\CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
			//sprawdzenie czy obrazek
			if ($record->class === 'image') {
				try {
					$thumb = new \Cms\Mvc\ViewHelper\Thumb();
					$url = $thumb->thumb($record, 'scalecrop', '100x60');
					if (!empty($url)) {
						return json_encode(['result' => 'OK', 'url' => $url]);
					}
				} catch (\Exception $ex) {
				}
			}
		}
		return $this->_jsonError(179);
	}
	
	/**
	 * Zwraca dane opisujące rekord pliku
	 */
	public function detailsAction() {
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		if (!$this->getPost()->cmsFileId) {
			return $this->_jsonError(185);
		}
		//szukamy rekordu pliku
		if (null !== $record = (new \Cms\Orm\CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
			return json_encode(['result' => 'OK', 'record' => $record]);
		}
		return $this->_jsonError(185);
	}
	
	/**
	 * Zapisuje kolejność plików
	 */
	public function sortAction() {
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		$order = $this->getPost()->order;
		if (empty($order) || !is_array($order)) {
			return json_encode(['result' => 'OK']);
		}
		try {
			\Cms\Model\File::sortBySerial($order);
		} catch (\Exception $ex) {
			return $this->_jsonError(180);
		}
		return json_encode(['result' => 'OK']);
	}
	
	/**
	 * Zwraca sformatowany błąd JSON
	 * @param integer $code
	 * @param string $message
	 * @return string
	 */
	protected function _jsonError($code = 403, $message = '') {
		return json_encode([
			'result' => 'ERR',
			'error' => [
				'code' => $code,
				'message' => $message
			]
		]);
	}

}
