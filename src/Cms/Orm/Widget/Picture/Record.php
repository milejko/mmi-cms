<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Widget\Picture;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $dateAdd;

	protected function _insert() {
		$this->dateAdd = date('Y-m-d H:i:s');
		return parent::_insert();
	}

	public function delete() {
		\Cms\Orm\File\Query::imagesByObject('cmswidgetpicture', $this->getPk())
			->find()
			->delete();
		return parent::delete();
	}

	public function getFirstImage() {
		$image = \Cms\Orm\File\Query::imagesByObject('cmswidgetpicture', $this->id)
			->findFirst();
		return $image;
	}

}
