<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Config;

class Navigation extends \Mmi\Navigation\Config {

	public static function getMenu() {
		return self::newElement()
				->setLabel('Panel administracyjny')
				->setModule('cmsAdmin')
				->setController('Index')
				->setVisible(true)
				->addChild(self::newElement()
					->setLabel('Zmiana hasła')
					->setModule('cmsAdmin')
					->setController('index')
					->setAction('password')
					->setVisible(false)
				)
				->addChild(self::_getContentPart());
	}

	protected static function _getContentPart() {
		return self::newElement()
				->setLabel('CMS')
				->setModule('cmsAdmin')
				->addChild(self::_getAdminPart())
				->addChild(Navigation\News::getMenu())
				->addChild(Navigation\Article::getMenu())
				->addChild(Navigation\Comment::getMenu())
				->addChild(Navigation\Contact::getMenu())
				->addChild(Navigation\Stat::getMenu())
				->addChild(Navigation\Page::getMenu())
				->addChild(Navigation\Text::getMenu());
	}

	protected static function _getAdminPart() {
		return self::newElement()
				->setLabel('Administracja')
				->setModule('cmsAdmin')
				->addChild(Navigation\Cron::getMenu())
				->addChild(Navigation\Log::getMenu())
				->addChild(Navigation\Mail::getMenu())
				->addChild(Navigation\Navigation::getMenu())
				->addChild(Navigation\File::getMenu())
				->addChild(Navigation\Route::getMenu())
				->addChild(Navigation\Acl::getMenu())
				->addChild(Navigation\Auth::getMenu());
	}

}
