<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Orm\Role;

class Record extends \Mmi\Orm\Record {

	public $id;
	public $name;

	public function save() {
		if (!parent::save()) {
			return false;
		}
		//zapis reguły dostępu do defaulta dla zapisanej roli
		$rule = new \Cms\Orm\Acl\Record();
		$rule->cmsRoleId = $this->id;
		$rule->module = 'mmi';
		$rule->access = 'allow';
		return $rule->save();
	}

}
