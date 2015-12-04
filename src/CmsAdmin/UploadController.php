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
