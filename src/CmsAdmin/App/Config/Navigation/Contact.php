<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App\Config\Navigation;

class Contact extends \Mmi\Navigation\Config {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Kontakt')
				->setModule('cmsAdmin')
				->setController('contact')
				->addChild(self::newElement()
					->setLabel('Tematy')
					->setModule('cmsAdmin')
					->setController('contact')
					->setAction('subject')
					->addChild(self::newElement()
						->setLabel('Dodaj')
						->setModule('cmsAdmin')
						->setController('contact')
						->setAction('editSubject')));
	}

}
