<?php

namespace Cms\Orm\Auth\Role;

//<editor-fold defaultstate="collapsed" desc="cms_auth_role Query">
/**
 * @method \Cms\Orm\Auth\Role\Query limit($limit = null)
 * @method \Cms\Orm\Auth\Role\Query offset($offset = null)
 * @method \Cms\Orm\Auth\Role\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Auth\Role\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Auth\Role\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Auth\Role\Query resetOrder()
 * @method \Cms\Orm\Auth\Role\Query resetWhere()
 * @method \Cms\Orm\Auth\Role\Query\Field whereId()
 * @method \Cms\Orm\Auth\Role\Query\Field andFieldId()
 * @method \Cms\Orm\Auth\Role\Query\Field orFieldId()
 * @method \Cms\Orm\Auth\Role\Query orderAscId()
 * @method \Cms\Orm\Auth\Role\Query orderDescId()
 * @method \Cms\Orm\Auth\Role\Query groupById()
 * @method \Cms\Orm\Auth\Role\Query\Field whereCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query\Field andFieldCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query\Field orFieldCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query orderAscCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query orderDescCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query groupByCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query\Field whereCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query\Field andFieldCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query\Field orFieldCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query orderAscCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query orderDescCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query groupByCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Auth\Role\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Auth\Role\Record[] find()
 * @method \Cms\Orm\Auth\Role\Record findFirst()
 * @method \Cms\Orm\Auth\Role\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_auth_role';

	/**
	 * @return \Cms\Orm\Auth\Role\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Zapytanie po authId
	 * @param integer $authId
	 * @return \Cms\Orm\Auth\Role\Query
	 */
	public static function byAuthId($authId) {
		return self::factory()
				->whereCmsAuthId()->equals($authId);
	}

	/**
	 * Zapytanie po authId z połączoną rolą
	 * @param integer $authId
	 * @return \Cms\Orm\Auth\Role\Query
	 */
	public static function joinedRolebyAuthId($authId) {
		return self::byAuthId($authId)
				->join('cms_role')->on('cms_role_id');
	}

}
