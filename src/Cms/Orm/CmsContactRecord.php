<?php

namespace Cms\Orm;

/**
 * Rekord kontaktu
 */
class CmsContactRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsContactOptionId;
	public $dateAdd;
	public $text;
	public $reply;
	public $cmsAuthIdReply;
	public $uri;
	public $name;
	public $phone;
	public $email;
	public $ip;
	public $cmsAuthId;
	public $active;

	/**
	 * Wstawienie rekordu
	 * @return boolean
	 */
	public function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		$this->ip = \Mmi\App\FrontController::getInstance()->getEnvironment()->remoteAddress;
		$this->active = 1;
		$auth = \App\Registry::$auth;
		//zapis znanego użytkownika
		if ($auth->hasIdentity()) {
			$this->cmsAuthId = $auth->getId();
		}
		$namespace = new \Mmi\Session\Space('contact');
		$this->uri = $namespace->referer;
		//wysyłka do maila zdefiniowanego w opcjach
		$option = CmsContactOptionQuery::factory()->findPk($this->cmsContactOptionId);
		//niepoprawna opcja
		if (!$option || !\Cms\Model\Mail::pushEmail('admin_cms_contact', $option->sendTo, ['contact' => $this, 'option' => $option], null, $this->email)) {
			return false;
		}
		return parent::_insert();
	}

}
