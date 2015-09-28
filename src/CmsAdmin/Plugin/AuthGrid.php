<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

class AuthGrid extends \CmsAdmin\Plugin\Grid {

	public function init() {

		$this->setQuery(\Cms\Orm\Auth\Query::factory());

		$this->setOption('locked', true);

		$this->addColumn('text', 'username', [
			'label' => 'nazwa użytkownika'
		]);

		$this->addColumn('text', 'email', [
			'label' => 'e-mail'
		]);

		$this->addColumn('text', 'name', [
			'label' => 'pełna nazwa użytkownika'
		]);

		$this->addColumn('text', 'lastLog', [
			'label' => 'ostatnio zalogowany'
		]);

		$this->addColumn('text', 'lastIp', [
			'label' => 'ostatni IP'
		]);

		$this->addColumn('text', 'lastFailLog', [
			'label' => 'błędne logowanie'
		]);

		$this->addColumn('text', 'lastFailIp', [
			'label' => 'IP błędnego logowania'
		]);

		$this->addColumn('checkbox', 'active', [
			'label' => 'aktywny'
		]);

		$this->addColumn('buttons', 'buttons', [
			'label' => 'operacje'
		]);
	}

}
