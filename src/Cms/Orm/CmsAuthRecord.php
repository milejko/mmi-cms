<?php

namespace Cms\Orm;

/**
 * Rekord użytkownika CMS
 */
class CmsAuthRecord extends \Mmi\Orm\Record {

	public $id;
	public $lang;
	public $name;
	public $username;
	public $email;
	public $password;
	public $lastIp;
	public $lastLog;
	public $lastFailIp;
	public $lastFailLog;
	public $failLogCount;
	public $logged;
	public $active;
	
	protected $_roles;
	
	/**
	 * Zwraca role użytkownika jako tablicę
	 * @return array
	 */
	public function getRoles() {
		if (is_array($this->_roles)) {
			return $this->_roles;
		}
		if (!$this->id) {
			return $this->_roles = [];
		}
		return $this->_roles = \Cms\Orm\CmsAuthRoleQuery::joinedRoleByAuthId($this->id)
			->orderAsc('name', 'cms_role')
			->findPairs('cms_role_id', 'cms_role.name');
	}
	
	/**
	 * Zwraca role użytkownika jako napis
	 * @return string
	 */
	public function getRolesAsString() {
		return implode(', ', $this->getRoles());
	}

}
