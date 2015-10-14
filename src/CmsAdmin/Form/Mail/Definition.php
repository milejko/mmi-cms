<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Mail;

/**
 * Klasa formularza szablonów maili
 */
class Definition extends \Mmi\Form\Form {

	public function init() {

		//nazwa
		$this->addElementText('name')
			->setLabel('unikalna nazwa')
			->setRequired()
			->addValidatorStringLength(6, 25)
			->addValidatorRecordUnique(\Cms\Orm\CmsMailDefinitionQuery::factory(), 'name', $this->_record->id);

		//wybór połączenia
		$this->addElementSelect('cmsMailServerId')
			->setLabel('połącznie')
			->setRequired()
			->setMultioptions(\Cms\Model\Mail::getMultioptions());

		//temat
		$this->addElementText('subject')
			->setLabel('Tytuł')
			->setRequired()
			->addValidatorStringLength(2, 240);

		//treść
		$this->addElementTextarea('message')
			->setLabel('treść')
			->setRequired();

		//treść html
		$this->addElementCheckbox('html')
			->setLabel('treść HTML')
			->setRequired();

		//od
		$this->addElementText('fromName')
			->setLabel('wyświetlana nazwa (od kogo)')
			->setDescription('np. Pomoc serwisu xyz.pl')
			->setRequired()
			->addValidatorStringLength(2, 240);

		//odpowiedz na
		$this->addElementText('replyTo')
			->setLabel('odpowiedz na')
			->setDescription('jeśli inny niż z którego wysłano wiadomość')
			->setRequired(false)
			->addValidatorStringLength(2, 240);

		//aktywny
		$this->addElementCheckbox('active')
			->setLabel('aktywny')
			//->setChecked()
			->setRequired();

		//submit
		$this->addElementSubmit('submit')
			->setLabel('zapisz mail')
			->setIgnore();
	}

}
