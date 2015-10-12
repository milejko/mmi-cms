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
 * Grid kontaktu
 */
class ContactGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(\Cms\Orm\CmsContactQuery::factory());
		
		//indeks
		$this->addColumnIndex();

		//id
		$this->addColumnCustom('id')
			->setTemplateCode('#{$record->id');

		//data dodania
		$this->addColumnText('dateAdd')
			->setLabel('data dodania');

		//tekst
		$this->addColumnText('text')
			->setLabel('zapytanie');

		//email
		$this->addColumnText('email')
			->setLabel('e-mail');

		//strona wejściowa
		$this->addColumnText('uri')
			->setLabel('strona wejściowa');

		//ip
		$this->addColumnText('ip')
			->setLabel('ip');

		//aktywny
		$this->addColumnCheckbox('active')
			->setLabel('czeka');

		//operacje
		$this->addColumnOperation()
			->setDeleteParams(['module' => 'cmsAdmin', 'controller' => 'contact', 'action' => 'edit', 'id' => '%id%'])
			->setEditParams(['module' => 'cmsAdmin', 'controller' => 'contact', 'action' => 'delete', 'id' => '%id%']);
	}

}
