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
 * Grid widgetów
 */
class CategoryWidgetGrid extends \CmsAdmin\Grid\Grid {

	public function init() {

		//query
		$this->setQuery((new \Cms\Orm\CmsCategoryWidgetQuery)
				->orderAscId());

		//nazwa
		$this->addColumnText('name')
			->setLabel('nazwa');
		
		$widgets = [null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard(3, '/widget/');

		//klasa modułu wyświetlania
		$this->addColumnSelect('mvcParams')
			->setMultioptions($widgets)
			->setLabel('moduł wyświetlania');

		//klasa modułu wyświetlania
		$this->addColumnSelect('mvcPreviewParams')
			->setMultioptions($widgets)
			->setLabel('modułu podglądu');

		//klasa forma
		$this->addColumnText('formClass')
			->setLabel('klasa formularza konfiguracji');

		//operacje
		$this->addColumnOperation();
	}

}
