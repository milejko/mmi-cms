<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

class Contact extends \Cms\Form\Form {

	public function init() {

		$this->setSecured();

		if (!$this->getOption('subjectId')) {
			$this->addElementSelect('cmsContactOptionId')
				->setLabel('Wybierz temat')
				->setMultiOptions(\Cms\Model\Contact::getMultioptions())
				->addValidatorInteger();
		}

		$auth = \App\Registry::$auth;

		$this->addElementText('email')
			->setLabel('Twój adres email')
			->setValue($auth->getEmail())
			->setRequired()
			->addValidatorEmailAddress();

		$this->addElementTextarea('text')
			->setLabel('Wiadomość')
			->setRequired()
			->addFilter('StripTags');

		//captcha dla niezalogowanych
		if (!($auth->getId() > 0)) {
			$this->addElementCaptcha('regCaptcha')
				->setLabel('Przepisz kod');
		}

		$this->addElementSubmit('submit')
			->setLabel('Wyślij');
	}

	/**
	 * Konwersja subjectId na cmsContactOptionId
	 * @return boolean
	 */
	public function beforeSave() {
		if ($this->getOption('subjectId') > 0) {
			$this->getElement('cmsContactOptionId')->setValue($this->getOption('subjectId'));
		}
		return true;
	}

}
