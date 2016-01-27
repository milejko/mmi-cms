<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz zmiany hasła w CMS
 * @method \Cms\Orm\CmsAuthRecord getRecord()
 */
class Password extends \Mmi\Form\Form {

	public function init() {

		$this->addElementPassword('password')
			->setLabel('obecne hasło')
			->setRequired()
			->addValidatorNotEmpty();

		$this->addElementPassword('changePassword')
			->setLabel('nowe hasło')
			->setDescription('wpisz nowe hasło, co najmniej 4 znaki')
			->setRequired()
			->addValidatorStringLength(4, 128);

		$this->addElementPassword('confirmPassword')
			->setLabel('powtórz nowe hasło')
			->setRequired()
			->addValidatorStringLength(4, 128);

		$this->addElementSubmit('change')
			->setLabel('Zmień hasło');
	}
	
	/**
	 * Zmiana hasła
	 * @return boolean
	 */
	public function beforeSave() {
		$auth = new \Cms\Model\Auth;
		$record = $auth->authenticate(\App\Registry::$auth->getUsername(), $this->getElement('password')->getValue());
		//logowanie niepoprawne
		if (!$record) {
			$this->getElement('password')->addError('Obecne hasło jest nieprawidłowe');
			return false;
		}
		//hasła niezgodne
		if ($this->getElement('changePassword')->getValue() != $this->getElement('confirmPassword')->getValue()) {
			$this->getElement('confirmPassword')->addError('Hasła niezgodne');
			return false;
		}
		//znajdowanie rekordu użytkownika
		$authRecord = (new \Cms\Orm\CmsAuthQuery)->findPk(\App\Registry::$auth->getId());
		if (null === $authRecord) {
			return false;
		}
		$authRecord->password = \Cms\Model\Auth::getSaltedPasswordHash($this->getElement('changePassword')->getValue());
		return $authRecord->save();
	}

}
