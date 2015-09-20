<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Comment;

/**
 * Rekord komentarza
 */
class Record extends \Mmi\Orm\Record {

	public $id;
	public $cmsAuthId;
	public $parentId;
	public $dateAdd;
	public $title;
	public $text;
	public $signature;
	public $ip;
	public $stars;
	public $object;
	public $objectId;

	/**
	 * Wstawienie rekordu
	 * @return boolean
	 */
	protected function _insert() {
		//data dodania
		$this->dateAdd = date('Y-m-d H:i:s');
		$this->signature = '~' . $this->signature;
		$this->ip = \Mmi\App\FrontController::getInstance()->getEnvironment()->remoteAddress;
		//dane z autoryzacji
		$auth = \App\Registry::$auth;
		if ($auth->hasIdentity()) {
			$this->signature = $auth->getUsername();
			$this->cmsAuthId = $auth->getId();
		}
		return parent::_insert();
	}

}
