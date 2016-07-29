<?php

namespace Cms\Orm;

class CmsAttributeGroupRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $name;
	public $key;
	public $description;
	
	public function save() {
		$this->key = (new \Mmi\Filter\Url)->filter($this->name);
		return parent::save();
	}

}
