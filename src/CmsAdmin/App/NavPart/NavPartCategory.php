<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

class NavPartCategory extends \Mmi\Navigation\NavigationConfig {

	public static function getMenu() {
		return (new \Mmi\Navigation\NavigationConfigElement)
				->setLabel('Treść')
				->setModule('cmsAdmin')
				->setController('category')
				->setAction('edit')
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setLabel('Lista treści')
					->setModule('cmsAdmin')
					->setController('category')
					->setAction('index')
					->addChild((new \Mmi\Navigation\NavigationConfigElement)
						->setTitle('Konfiguracja widgeta')
						->setModule('cmsAdmin')
						->setController('categoryWidgetRelation')
						->setAction('config')
						->setDisabled(true)
					)
					->addChild((new \Mmi\Navigation\NavigationConfigElement)
						->setTitle('Nowy widget')
						->setModule('cmsAdmin')
						->setController('categoryWidgetRelation')
						->setAction('add')
						->setDisabled(true)
					)
				)
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setLabel('Szablony')
					->setModule('cmsAdmin')
					->setController('categoryType')
					->setAction('index')
					->addChild((new \Mmi\Navigation\NavigationConfigElement)
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('categoryType')
						->setAction('edit'))
				)
				->addChild((new \Mmi\Navigation\NavigationConfigElement)
					->setLabel('Widgety')
					->setModule('cmsAdmin')
					->setController('categoryWidget')
					->setAction('index')
					->addChild((new \Mmi\Navigation\NavigationConfigElement)
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('categoryWidget')
						->setAction('edit'))
		);
	}

}
