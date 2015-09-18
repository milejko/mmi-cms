<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Contact;

/**
 * Rekord kontaktu poprzez formularz
 */
class Record extends \Mmi\Orm\Record {

	public $id;
	public $cmsContactOptionId;
	public $dateAdd;
	public $text;
	public $reply;
	public $cmsAuthIdReply;
	public $uri;
	public $email;
	public $ip;
	public $cmsAuthId;
	public $active;
	public $name;
	public $phone;

	public function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		$this->ip = \Mmi\Controller\Front::getInstance()->getEnvironment()->remoteAddress;
		$this->active = 1;
		$auth = \App\Registry::$auth;
		if ($auth->hasIdentity()) {
			$this->cmsAuthId = $auth->getId();
		}
		$namespace = new \Mmi\Session\Space('contact');
		$this->uri = $namespace->referer;
		//wysyłka do maila zdefiniowanego w opcjach
		$option = \Cms\Orm\Contact\Option\Query::factory()->findPk($this->cmsContactOptionId);
		if (!$option || !\Cms\Model\Mail::pushEmail('admin_cms_contact', $option->sendTo, ['contact' => $this, 'option' => $option], null, $this->email)) {
			return false;
		}
		return parent::_insert();
	}

}
