<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;
use \Cms\Orm;

class Acl {

	/**
	 * Multiopcje po roli
	 * @param string $role
	 * @return array
	 */
	public static function getMultioptionsByRoleId($role) {
		$rules = [];
		$data = Orm\Acl\Query::factory()
			->whereCmsRoleId()->equals($role)
			->find();
		foreach ($data as $item) {
			if ($item->action) {
				$rules[$item->module . ':' . $item->controller . ':' . $item->action] = $item;
			} elseif ($item->controller) {
				$rules[$item->module . ':' . $item->controller] = $item;
			} else {
				$rules[$item->module] = $item;
			}
		}
		return $rules;
	}

	/**
	 * Ustawianie ACL'a
	 * @return \Mmi\Acl
	 */
	public static function setupAcl() {
		$acl = new \Mmi\Acl();
		$aclData = Orm\Acl\Query::factory()
			->join('cms_role')->on('cms_role_id')
			->find();
		foreach ($aclData as $aclRule) { /* @var $aclData \Cms\Orm\Acl\Record */
			$resource = '';
			if ($aclRule->module) {
				$resource .= $aclRule->module . ':';
			}
			if ($aclRule->controller) {
				$resource .= $aclRule->controller . ':';
			}
			if ($aclRule->action) {
				$resource .= $aclRule->action . ':';
			}
			$access = $aclRule->access;
			if ($access == 'allow' || $access == 'deny') {
				$acl->$access($aclRule->getJoined('cms_role')->name, trim($resource, ':'));
			}
		}
		return $acl;
	}

}
