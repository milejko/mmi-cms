<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

abstract class Action Extends \Mmi\Controller\Action {

	public function init() {
		//ustawienie języka edycji
		$session = new \Mmi\Session\Space('cms-language');
		$lang = in_array($session->lang, \App\Registry::$config->languages) ? $session->lang : null;
		if ($lang === null && isset(\App\Registry::$config->languages[0])) {
			$lang = \App\Registry::$config->languages[0];
		}
		unset($this->getRequest()->lang);
		unset(\Mmi\App\FrontController::getInstance()->getRequest()->lang);
		if ($lang !== null) {
			\Mmi\App\FrontController::getInstance()->getRequest()->lang = $lang;
			$this->getRequest()->lang = $lang;
		}
		\Mmi\App\FrontController::getInstance()->getResponse()->setHeader('X-UA-Compatible', 'IE=EmulateIE10', true);
	}

}
