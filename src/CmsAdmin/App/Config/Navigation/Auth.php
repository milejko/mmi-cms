<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Config\Navigation;

class Auth extends \Mmi\Navigation\Config {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Użytkownicy')
				->setModule('cmsAdmin')
				->setController('auth')
				->addChild(self::newElement()
					->setLabel('Dodaj')
					->setModule('cmsAdmin')
					->setController('auth')
					->setAction('edit')
		);
	}

}
