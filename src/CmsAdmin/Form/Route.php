<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class Route extends \Mmi\Form {

	public function init() {

		$this->addElementText('pattern')
			->setLabel('Wzorzec')
			->setRequired()
			->addValidatorNotEmpty();

		$this->addElementText('replace')
			->setLabel('Tabela zastąpień')
			->setRequired()
			->setDescription('zmienna1=foo&zmienna2=bar')
			->addValidatorNotEmpty();

		$this->addElementText('default')
			->setLabel('Tabela wartości domyślnych')
			->setDescription('zmienna1=foo&zmienna2=bar');

		$this->addElementText('order')
			->setLabel('Indeks kolejności')
			->setRequired()
			->addValidatorInteger();

		$this->addElementSelect('active')
			->setLabel('Aktywna')
			->setMultiOptions([0 => 'nie', '1' => 'tak'])
			->addValidatorInteger();

		$this->addElementSubmit('submit')
			->setLabel('Zapisz trasę');
	}

}
