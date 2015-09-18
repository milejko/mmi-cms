<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\View\Helper;

class Text extends \Mmi\View\Helper\HelperAbstract {

	/**
	 * Generuje tekst statyczny
	 * @param string $key klucz
	 * @return string
	 */
	public function text($key) {
		return nl2br(\Cms\Model\Text::textByKeyLang($key, \Mmi\Controller\Front::getInstance()->getView()->request->lang));
	}

}
