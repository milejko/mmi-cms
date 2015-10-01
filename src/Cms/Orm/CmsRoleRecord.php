<?php

namespace Cms\Orm;

/**
 * Rekord roli
 */
class CmsRoleRecord extends \Mmi\Orm\Record {

	public $id;
	public $name;

	/**
	 * Zapis roli
	 * @return boolean
	 */
	public function save() {
		if (!parent::save()) {
			return false;
		}
		//zapis reguły dostępu do defaulta dla zapisanej roli
		$rule = new \Cms\Orm\CmsAclRecord();
		$rule->cmsRoleId = $this->id;
		$rule->module = 'mmi';
		$rule->access = 'allow';
		//zapis reguły acl
		return $rule->save();
	}

}
