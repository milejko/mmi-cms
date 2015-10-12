<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Plugin;

/**
 * Router z CMS
 */
class RouteGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(\Cms\Orm\CmsRouteQuery::factory()
				->orderAscOrder());

		//pattern
		$this->addColumnText('pattern')
			->setLabel('wzorzec');

		//zamiany
		$this->addColumnText('replace')
			->setLabel('tabela zamian');

		//domyślne
		$this->addColumnText('default')
			->setLabel('tabela wartości domyślnych');

		//kolejność
		$this->addColumnText('order')
			->setLabel('indeks kolejności');

		//aktywna
		$this->addColumnCheckbox('active')
			->setLabel('aktywna');

		//operacje
		$this->addColumnOperation();
	}

}
