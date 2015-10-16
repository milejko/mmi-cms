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
 * Grid opisu statystyk
 */
class StatLabelGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(new \Cms\Orm\CmsStatLabelQuery);

		//obiekt
		$this->addColumnText('object')
			->setLabel('klucz');

		//nazwa
		$this->addColumnText('label')
			->setLabel('nazwa statystyki');

		//opis
		$this->addColumnText('description')
			->setLabel('opis');

		//operacje bez usuwania
		$this->addColumnOperation()
			->setDeleteParams([]);
	}

}
