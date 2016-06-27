<?php

namespace Cms\Orm;

class CmsCategoryRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $name;
	public $description;
	public $uri;
	public $parentId;
	public $order;
	public $dateAdd;
	public $dateModify;
	public $active;
	
	public function save() {
		//ustawiamy uri
		if ($this->parentId && (null !== $parent = (new CmsCategoryQuery)->findPk($this->parentId))) {
			$this->uri = $parent->uri . '/';
		}
		//usunięcie uri jeśli usunięty parentId
		if ($this->parentId === null) {
			$this->uri = null;
		}
		//doklejanie do uri przefiltrowanej końcówki
		$this->uri .= (new \Mmi\Filter\Url)->filter($this->name);
		return parent::save();
	}

	/**
	 * Wstawienie kategorii z obliczeniem kodu i przebudową drzewa
	 * @return boolean
	 */
	protected function _insert() {
		//data aktualizacji
		$this->dateAdd = date('Y-m-d H:i:s');
		//próba utworzenia rekordu
		return parent::_insert();
	}

	/**
	 * Aktualizacja kategorii
	 * @return boolean
	 */
	protected function _update() {
		$parentModified = false;
		//zmodyfikowany parent
		if ($this->isModified('parentId')) {
			$parentModified = true;
		}
		$this->dateModify = date('Y-m-d H:i:s');
		//aktualizacja rekordu
		if (!parent::_update()) {
			return false;
		}
		//przebudowa dzieci
		if ($parentModified) {
			$this->_rebuildChildren($this->id);
		}
	}
	
	protected function _rebuildChildren($id) {
		foreach ((new CmsCategoryQuery)
			->whereParentId()->equals($id)
			->orderAscOrder()
			->find() as $categoryRecord) {
			$categoryRecord->save();
			$this->_rebuildChildren($categoryRecord->id);
		}
	}	

}
