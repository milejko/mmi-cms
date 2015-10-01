<?php

namespace Cms\Orm;

/**
 * Rekord aktualności
 */
class CmsNewsRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $title;
	public $lead;
	public $text;
	public $dateAdd;
	public $dateModify;
	public $uri;
	public $internal;
	public $visible;

	/**
	 * Zapis rekordu
	 * @return boolean
	 */
	public function save() {
		$filter = new \Mmi\Filter\Url();
		$uri = $filter->filter($this->title);
		//identyfikatory dla linków wewnętrznych
		if ($this->internal == 1) {
			$exists = CmsNewsQuery::byUri($uri)
				->findFirst();
			if ($exists !== null && $exists->getPk() != $this->getPk()) {
				$uri = $uri . '_' . date('Y-m-d');
			}
			$this->uri = $uri;
		}
		$this->lang = \Mmi\App\FrontController::getInstance()->getRequest()->lang;
		$this->dateModify = date('Y-m-d H:i:s');
		return parent::save();
	}

	/**
	 * Pobiera pierwszy obraz
	 * @return \Cms\Orm\CmsFileRecord
	 */
	public function getFirstImage() {
		return CmsFileQuery::imagesByObject('cmsnews', $this->id)
				->findFirst();
	}

	/**
	 * Wstawienie rekordu
	 * @return boolean
	 */
	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

	/**
	 * Usunięcie rekordu
	 * @return boolean
	 */
	public function delete() {
		CmsFileQuery::imagesByObject('cmsnews', $this->getPk())
			->find()
			->delete();
		return parent::delete();
	}

}
