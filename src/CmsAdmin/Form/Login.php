<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class Login extends \Mmi\Form\Form {

	public function init() {

		$this->addElementText('username')
			->setLabel('nazwa użytkownika')
			->setDescription('Wpisz swój unikalny identyfikator')
			->addFilterStringTrim();

		$this->addElementPassword('password')
			->setLabel('hasło')
			->addValidatorStringLength(4, 128);

		$this->addElementSubmit('login')
			->setLabel('Zaloguj się');
	}

	/**
	 * Logowanie
	 * @return boolean
	 */
	public function beforeSave() {
		//brak loginu lub hasła
		if (!$this->getElement('username')->getValue() || !$this->getElement('password')->getValue()) {
			return false;
		}
		//autoryzacja
		$auth = \App\Registry::$auth;
		\App\Registry::$auth->setIdentity($this->getElement('username')->getValue());
		\App\Registry::$auth->setCredential($this->getElement('password')->getValue());
		return \App\Registry::$auth->authenticate();
	}

}
