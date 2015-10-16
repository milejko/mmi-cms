<?php

namespace Cms\Orm;

/**
 * Rekord kontaktu
 */
class CmsContactRecord extends \Mmi\Orm\Record {

	/**
	 * Identyfikator
	 * @var integer
	 */
	public $id;
	
	/**
	 * Klucz obcy ID opcji
	 * @var type 
	 */
	public $cmsContactOptionId;
	
	/**
	 * Data dodania
	 * @var data dodania
	 */
	public $dateAdd;
	public $text;
	public $reply;
	
	/**
	 * Identyfikator użytkownika odpowiadającego
	 * @var integer
	 */
	public $cmsAuthIdReply;
	public $uri;
	public $name;
	public $phone;
	public $email;
	public $ip;
	
	/**
	 * Identyfikator dodającego użytkownika (jeśli zalogowany)
	 * @var integer
	 */
	public $cmsAuthId;
	public $active;

	/**
	 * Wstawienie rekordu
	 * @return boolean
	 */
	public function _insert() {
		//data dodania
		$this->dateAdd = date('Y-m-d H:i:s');
		//adres IP
		$this->ip = \Mmi\App\FrontController::getInstance()->getEnvironment()->remoteAddress;
		$this->active = 1;
		//zapis znanego użytkownika
		if (\App\Registry::$auth->hasIdentity()) {
			$this->cmsAuthId = \App\Registry::$auth->getId();
		}
		//namespace w sesji
		$namespace = new \Mmi\Session\SessionSpace('contact');
		$this->uri = $namespace->referer;
		//wysyłka do maila zdefiniowanego w opcjach
		$option = (new CmsContactOptionQuery)->findPk($this->cmsContactOptionId);
		//niepoprawna opcja
		if (!$option || !\Cms\Model\Mail::pushEmail('admin_cms_contact', $option->sendTo, ['contact' => $this, 'option' => $option], null, $this->email)) {
			return false;
		}
		return parent::_insert();
	}

}
