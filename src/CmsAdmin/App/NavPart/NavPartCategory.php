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
		return self::newElement()
				->setLabel('Kategorie')
				->setModule('cmsAdmin')
				->setController('category')
				->addChild(self::newElement()
					->setLabel('Typy')
					->setModule('cmsAdmin')
					->setController('categoryType')
					->setAction('index')
					->addChild(self::newElement()
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('categoryType')
						->setAction('edit'))
		);
	}

}
