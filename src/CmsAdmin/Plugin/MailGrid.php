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
 * Grid maila
 */
class MailGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery((new \Cms\Orm\CmsMailQuery)
				->orderDescId());

		//wysłany
		$this->addColumnSelect('active')
			->setMultioptions([
				0 => 'do wysyłki',
				1 => 'wysłany',
				2 => 'w trakcie wysyłki',
			])
			->setLabel('wysłany');

		//data dodania
		$this->addColumnText('dateAdd')
			->setLabel('data dodania');

		//data wysyłki
		$this->addColumnText('dateSent')
			->setLabel('data wysłania');

		//do
		$this->addColumnText('to')
			->setLabel('do');

		//temat
		$this->addColumnText('subject')
			->setLabel('temat');

		//nazwa od
		$this->addColumnText('fromName')
			->setLabel('od');

		//operacje
		$this->addColumnOperation()
			->setEditParams(['module' => 'cmsAdmin', 'controller' => 'mail', 'action' => 'preview', 'id' => '%id%']);
	}

}
