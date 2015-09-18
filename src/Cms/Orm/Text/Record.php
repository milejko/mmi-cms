<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Text;

/**
 * Rekord tekstu stałego
 */
class Record extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $key;
	public $content;
	public $dateModify;

	public function save() {
		//data modyfikacji
		$this->dateModify = date('Y-m-d H:i:s');
		$this->lang = \Mmi\Controller\Front::getInstance()->getRequest()->lang;
		//usunięcie kompilantów
		foreach (glob(BASE_PATH . '/var/compile/' . $this->lang . '_*.php') as $compilant) {
			unlink($compilant);
		}
		try {
			$result = parent::save();
		} catch (\Exception $e) {
			//duplikat
			return false;
		}
		//usunięcie cache
		\App\Registry::$cache->remove('\Cms\Text');
		return $result;
	}

}
