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
 * Grid definicji maila
 */
class MailDefinitionGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//zapytanie
		$this->setQuery(\Cms\Orm\CmsMailDefinitionQuery::lang());

		//język
		$this->addColumnCustom('lang')
			->setLabel('język');

		//nazwa
		$this->addColumnText('name')
			->setLabel('nazwa');

		//treść w html
		$this->addColumnCheckbox('html')
			->setLabel('HTML');

		//temat
		$this->addColumnText('subject')
			->setLabel('temat');

		//nazwa od
		$this->addColumnText('fromName')
			->setLabel('nazwa od');

		//odpowiedz
		$this->addColumnText('replyTo')
			->setLabel('odpowiedz');
		
		//serwer
		$this->addColumnSelect('cmsMailServerId')
			->setMultioptions((new \Cms\Orm\CmsMailServerQuery)->findPairs('id', 'address'))
			->setLabel('serwer');

		//data dodania
		$this->addColumnText('dateAdd')
			->setLabel('data dodania');

		//data modyfikacji
		$this->addColumnText('dateModify')
			->setLabel('data modyfikacji');

		//aktywny
		$this->addColumnCheckbox('active')
			->setLabel('aktywny');

		//operacje
		$this->addColumnOperation();
	}

}
