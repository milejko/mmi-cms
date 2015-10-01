<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz odpowiedzi na kontakt
 * @method \Cms\Orm\CmsContactRecord getRecord()
 */
class Contact extends \Mmi\Form\Form {

	public function init() {

		//identyfikator tematu
		if (!$this->getOption('subjectId')) {
			$this->addElementSelect('cmsContactOptionId')
				->setDisabled()
				->setIgnore()
				->setValue($this->getOption('subjectId'))
				->setMultiOptions(\Cms\Model\Contact::getMultioptions())
				->setLabel('temat zapytania');
		}

		//mail
		$this->addElementText('email')
			->setDisabled()
			->setLabel('email')
			->setValue(\App\Registry::$auth->getEmail())
			->addValidatorEmailAddress();

		//tresc zapytania
		$this->addElementTextarea('text')
			->setDisabled()
			->setLabel('treść zapytania');

		//odpowiedz na zgloszenie
		$this->addElementTextarea('reply')
			->setRequired()
			->setLabel('odpowiedź');

		$this->addElementSubmit('submit')
			->setLabel('odpowiedz');
	}

	/**
	 * Ustawienie opcji przed zapisem
	 * @return boolean
	 */
	public function beforeSave() {
		$this->getRecord()->active = 0;
		$this->getRecord()->cmsAuthIdReply = \App\Registry::$auth->getId();
		return true;
	}

	/**
	 * Po zapisie wysyłka maila
	 * @return boolean
	 */
	public function afterSave() {
		\Cms\Model\Mail::pushEmail('contact_reply', $this->getRecord()->email, [
				'id' => $this->getRecord()->id,
				'text' => $this->getRecord()->text,
				'replyText' => $this->getElement('reply')->getValue()
		]);
		return true;
	}

}
