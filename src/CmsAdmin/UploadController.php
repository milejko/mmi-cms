<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
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
		$pluploadHandler = new Model\PluploadHandler();
		//jeśli wystąpił błąd
		if (!$pluploadHandler->handle()) {
			return $this->_jsonError($pluploadHandler->getErrorCode(), $pluploadHandler->getErrorMessage());
		}
		//jeśli wykonać operację po przesłaniu całego pliku i zapisaniu rekordu
		if ($this->getPost()->afterUpload && null !== $record = $pluploadHandler->getSavedCmsFileRecord()) {
			$this->_operationAfter($this->getPost()->afterUpload, $record);
		}
		return json_encode(['result' => 'OK', 'cmsFileId' => $pluploadHandler->getSavedCmsFileId()]);
	}
	
	/**
	 * Zwraca listę aktualnych plików przypiętych do obiektu formularza
	 */
	public function currentAction() {
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		$objectId = !empty($this->getPost()->objectId) ? $this->getPost()->objectId : null;
		switch ($this->getPost()->fileTypes) {
			case 'images' :
				//zapytanie o obrazki
				$query = \Cms\Orm\CmsFileQuery::imagesByObject($this->getPost()->object, $objectId);
				break;
			case 'notImages' :
				//wszystkie pliki bez obrazków
				$query = \Cms\Orm\CmsFileQuery::notImagesByObject($this->getPost()->object, $objectId);
				break;
			default :
				//domyślne zapytanie o wszystkie pliki
				$query = \Cms\Orm\CmsFileQuery::byObject($this->getPost()->object, $objectId);
		}
		//zwrot json'a z plikami
		return json_encode([
			'result' => 'OK',
			'files' => $query->orderAscDateAdd()
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
		//szukamy rekordu pliku
		if (!$this->getPost()->cmsFileId || null === $record = (new \Cms\Orm\CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
			return $this->_jsonError(178);
		}
		//sprawdzenie zgodności z obiektem formularza
		if ($record->object === $this->getPost()->object && $record->objectId == $this->getPost()->objectId) {
			//usuwanie
			if ($record->delete()) {
				//jeśli wykonać operację po usunięciu
				if ($this->getPost()->afterDelete) {
					$this->_operationAfter($this->getPost()->afterDelete, $record);
				}
				return json_encode(['result' => 'OK']);
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
	 * Aktualizuje dane opisujące rekord pliku: tytuł, autora, źródło
	 */
	public function describeAction() {
		$this->view->setLayoutDisabled();
		$this->getResponse()->setTypeJson(true);
		//sprawdzamy, czy jest id pliku i form
		if (!$this->getPost()->cmsFileId || !is_array($this->getPost()->form)) {
			return $this->_jsonError(186);
		}
		//szukamy rekordu pliku
		if (null === $record = (new \Cms\Orm\CmsFileQuery)->findPk($this->getPost()->cmsFileId)) {
			return $this->_jsonError(186);
		}
		$form = ['active' => 0, 'sticky' => 0];
		foreach ($this->getPost()->form as $field) {
			$form[$field['name']] = $field['value'];
		}
		$record->setFromArray($form);
		if ($form['sticky']) {
			$result = $record->setSticky();
		} else {
			$result = $record->save();
		}
		if ($result) {
			//jeśli wykonać operację po edycji
			if ($this->getPost()->afterEdit) {
				$this->_operationAfter($this->getPost()->afterEdit, $record);
			}
			return json_encode(['result' => 'OK']);
		}
		return $this->_jsonError(186);
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
	
	/**
	 * Wykonuje dodatkową operację po innym zdarzeniu
	 * @param array $action
	 * @param \Cms\Orm\CmsFileRecord $record
	 * @return mixed
	 */
	protected function _operationAfter($action, $record) {
		$params = array_merge($record->toArray(), $action);
		return \Mmi\Mvc\ActionHelper::getInstance()->action($params);
	}

}
