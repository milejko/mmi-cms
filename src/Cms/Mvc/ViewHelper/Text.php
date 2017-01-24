<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Mvc\ViewHelper;

/**
 * Helper tekstów statycznych
 */
class Text extends \Mmi\Mvc\ViewHelper\HelperAbstract {

	/**
	 * Generuje tekst statyczny
	 * @param string $key klucz
	 * @return string
	 */
	public function text($key) {
		return nl2br(\Cms\Model\Text::textByKeyLang($key, \Mmi\App\FrontController::getInstance()->getView()->request->lang));
	}

}
