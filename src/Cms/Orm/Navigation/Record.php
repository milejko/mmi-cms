<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Navigation;

/**
 * Rekord elementu nawigacji
 */
class Record extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $parentId;
	public $order;
	public $module;
	public $controller;
	public $action;

	/**
	 * Parametry request
	 * @var array
	 */
	public $params;

	/**
	 * Nazwa w menu
	 * @var string
	 */
	public $label;

	/**
	 * Tytuł dla <title>
	 * @var string
	 */
	public $title;
	public $keywords;
	public $description;
	public $uri;
	public $visible;
	public $dateStart;
	public $dateEnd;

	/**
	 * Czy link absolutny
	 * @var boolean
	 */
	public $absolute;

	/**
	 * Określa czy meta mają dziedziczyć z rodziców
	 * @var boolean
	 */
	public $independent;

	/**
	 * Czy nofollow
	 * @var boolean
	 */
	public $nofollow;

	/**
	 * Czy _blank
	 * @var boolean
	 */
	public $blank;

	/**
	 * Czy https (jeśli null - bez zmian)
	 * @var boolean
	 */
	public $https;

	/**
	 * Aktywny
	 * @var boolean
	 */
	public $active;

	/**
	 * Zapis z usunięciem cache
	 */
	public function save() {
		//ustawianie domyślnego języka
		$this->lang = \Mmi\Controller\Front::getInstance()->getRequest()->lang;
		//ustawienie rodzica
		$this->parentId = $this->parentId !== null ? $this->parentId : 0;
		return $this->_clearCache() && parent::save();
	}

	/**
	 * Dodawanie rekordu
	 * @return boolean
	 */
	public function _insert() {
		//dodawanie na końcu listy
		if ($this->parentId) {
			$lastElement = \Cms\Orm\Navigation\Query::descByParentId($this->parentId)
				->findFirst();
			$this->order = 0;
			//ustawianie za ostatnim elementem
			if ($lastElement !== null) {
				$this->order = $lastElement->order + 1;
			}
		}
		//insert rekordu
		return parent::_insert();
	}

	/**
	 * Usunięcie rekordu, rekordów poniżej + czyszczenie cache
	 * @return boolean
	 */
	public function delete() {
		//usunięcie podrzędnych
		\Cms\Orm\Navigation\Query::byParentId($this->id)
			->find()
			->delete();
		return $this->_clearCache() && parent::delete();
	}

	/**
	 * Usunięcie cache nawigatora i ACL'a
	 */
	protected function _clearCache() {
		\App\Registry::$cache->remove('Mmi-Navigation-');
		\App\Registry::$cache->remove('Mmi-Navigation-' . \Mmi\Controller\Front::getInstance()->getRequest()->lang);
		\App\Registry::$cache->remove('Mmi-Acl');
		return true;
	}

}
