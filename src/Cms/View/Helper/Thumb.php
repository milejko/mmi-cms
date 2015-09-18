<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\View\Helper;

class Thumb extends \Mmi\View\Helper\HelperAbstract {

	/**
	 * Metoda główna, generuje miniaturę
	 * @param \Cms\Orm\File\Record $file instancja pliku
	 * @param string $type skala
	 * @param string $value
	 * @param boolean $absolute absolutny link
	 * @return string
	 */
	public function thumb(\Cms\Orm\File\Record $file, $type = null, $value = null, $absolute = false) {
		$url = $file->getUrl($type, $value, $absolute);
		return $url;
	}

}
