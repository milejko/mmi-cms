<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Mail;

class Server extends \Mmi\Form\Component {

	public function init() {

		$this->addElementText('address')
			->setLabel('Adres serwera SMTP');

		$this->addElementSelect('ssl')
			->setLabel('Rodzaj połączenia')
			->setRequired()
			->setMultiOptions(['plain' => 'plain', 'tls' => 'tls', 'ssl' => 'ssl']);

		$this->addElementText('port')
			->setLabel('Port')
			->setRequired()
			->addValidatorInteger(true)
			->setValue(25)
			->setDescription('Plain: 25, SSL: 465, TLS: 587');

		$this->addElementText('username')
			->setLabel('Nazwa użytkownika');

		$this->addElementText('password')
			->setLabel('Hasło użytkownika');

		$this->addElementText('from')
			->setLabel('Domyślny adres od');

		//submit
		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

}
