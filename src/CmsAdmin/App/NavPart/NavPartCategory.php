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
				->setLabel('Treść')
				->setModule('cmsAdmin')
				->setController('category')
				->setAction('edit')
				->addChild(self::newElement()
					->setLabel('Lista treści')
					->setModule('cmsAdmin')
					->setController('category')
					->setAction('index')
				)
				->addChild(self::newElement()
					->setLabel('Szablony')
					->setModule('cmsAdmin')
					->setController('categoryType')
					->setAction('index')
					->addChild(self::newElement()
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('categoryType')
						->setAction('edit'))
				)
				->addChild(self::newElement()
					->setLabel('Widgety')
					->setModule('cmsAdmin')
					->setController('categoryWidget')
					->setAction('index')
					->addChild(self::newElement()
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('categoryWidget')
						->setAction('edit'))
		);
	}

}
