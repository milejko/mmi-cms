<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Grid użytkowników
 */
class AuthGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//domyślne zapytanie
		$this->setQuery(new \Cms\Orm\CmsAuthQuery);

		//nazwa
		$this->addColumnText('username')
			->setLabel('nazwa użytkownika');

		//email
		$this->addColumnText('email')
			->setLabel('e-mail');

		//imię
		$this->addColumnText('name')
			->setLabel('pełna nazwa użytkownika');

		//ostatnie logowanie
		$this->addColumnText('lastLog')
			->setLabel('ostatnio zalogowany');

		//ostatnie IP
		$this->addColumnText('lastIp')
			->setLabel('ostatni IP');

		//błędne logowanie
		$this->addColumnText('lastFailLog')
			->setLabel('błędne logowanie');

		//ostatnie ip błędnego logowania
		$this->addColumnText('lastFailIp')
			->setLabel('IP błędnego logowania');

		//aktywny
		$this->addColumnCheckbox('active')
			->setLabel('aktywny');
		
		//operacje
		$this->addColumnOperation();
	}

}
