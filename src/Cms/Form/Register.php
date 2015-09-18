<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

/**
 * Formularz rejestracji
 * @method type getRecord()
 */
class Register extends \Cms\Form {

	public function init() {

		$this->addElementText('username')
			->setLabel('nazwa użytkownika (nick)')
			->setRequired()
			->addValidatorAlnum()
			->addValidatorRecordUnique(\Cms\Orm\Auth\Query::factory(), 'username')
			->addValidatorStringLength(4, 25)
			->addFilter('lowercase');

		$this->addElementText('email')
			->setLabel('e-mail')
			->setRequired()
			->addValidatorEmailAddress()
			->addValidatorRecordUnique(\Cms\Orm\Auth\Query::factory(), 'email')
			->addValidatorStringLength(4, 150)
			->addFilter('lowercase');

		// Create and configure password element:
		$this->addElementPassword('password')
			->setLabel('hasło')
			->setRequired()
			->addValidatorStringLength(4, 64);

		$this->addElementPassword('confirmPassword')
			->setLabel('potwierdź hasło');

		$this->addElementCheckbox('regulations')
			->setLabel('Akceptuję regulamin')
			->setRequired();

		$this->addElementSubmit('submit')
			->setLabel('Zarejestruj');
	}
	
	/**
	 * Sprawdzanie zgodności haseł
	 * @return boolean
	 */
	public function beforeSave() {
		if ($this->getElement('password')->getValue() != $this->getElement('confirmPassword')->getValue()) {
			$this->getElement('confirmPassword')->addError('Hasła niezgodne');
			return false;
		}
		//opcja zmiany (w tym przypadku ustawienia nowego) hasłą
		$this->getRecord()->password = \Cms\Model\Auth::getSaltedPasswordHash($this->getElement('password')->getValue());
		//domyślny język
		$this->getRecord()->lang = 'pl';
		return true;
	}

}
