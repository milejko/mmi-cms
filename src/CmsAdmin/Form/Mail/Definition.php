<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Mail;

class Definition extends \Mmi\Form\Form {

	public function init() {

		$this->addElementText('name')
			->setLabel('Unikalna nazwa')
			->setRequired()
			->addValidatorStringLength(6, 25)
			->addValidatorRecordUnique(\Cms\Orm\Mail\Definition\Query::factory(), 'name', $this->_record->id);

		$this->addElementSelect('cmsMailServerId')
			->setLabel('Połącznie')
			->setRequired()
			->setMultiOptions(\Cms\Model\Mail::getMultioptions());

		$this->addElementText('subject')
			->setLabel('Tytuł')
			->setRequired()
			->addValidatorStringLength(2, 240);

		$this->addElementTextarea('message')
			->setLabel('Treść')
			->setRequired();

		$this->addElementCheckbox('html')
			->setLabel('Wiadomość HTML')
			->setRequired();

		$this->addElementText('fromName')
			->setLabel('Wyświetlana nazwa (Od kogo)')
			->setDescription('np. Pomoc serwisu xyz.pl')
			->setRequired()
			->addValidatorStringLength(2, 240);

		$this->addElementText('replyTo')
			->setLabel('Odpowiedz na')
			->setDescription('Jeśli inny niż z którego wysłano wiadomość')
			->setRequired(false)
			->addValidatorStringLength(2, 240);

		$this->addElementCheckbox('active')
			->setLabel('aktywny')
			->setValue(1)
			->setRequired();

		//submit
		$this->addElementSubmit('submit')
			->setLabel('zapisz mail')
			->setIgnore();
	}

}
