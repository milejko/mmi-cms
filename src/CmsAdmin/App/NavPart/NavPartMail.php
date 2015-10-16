<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\NavPart;

class NavPartMail extends \Mmi\Navigation\NavigationConfig {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Poczta')
				->setModule('cmsAdmin')
				->setController('mail')
				->addChild(self::newElement()
					->setLabel('Wyślij z kolejki')
					->setModule('cmsAdmin')
					->setController('mail')
					->setAction('send'))
				->addChild(self::newElement()
					->setLabel('Szablony')
					->setModule('cmsAdmin')
					->setController('mailDefinition')
					->addChild(self::newElement()
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('mailDefinition')
						->setAction('edit')))
				->addChild(self::newElement()
					->setLabel('Serwery')
					->setModule('cmsAdmin')
					->setController('mailServer')
					->addChild(self::newElement()
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('mailServer')
						->setAction('edit')));
	}

}
