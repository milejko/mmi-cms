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
 * @method \Cms\Orm\Auth\Role\QueryField whereId()
 * @method \Cms\Orm\Auth\Role\QueryField andFieldId()
 * @method \Cms\Orm\Auth\Role\QueryField orFieldId()
 * @method \Cms\Orm\Auth\Role\Query orderAscId()
 * @method \Cms\Orm\Auth\Role\Query orderDescId()
 * @method \Cms\Orm\Auth\Role\Query groupById()
 * @method \Cms\Orm\Auth\Role\QueryField whereCmsAuthId()
 * @method \Cms\Orm\Auth\Role\QueryField andFieldCmsAuthId()
 * @method \Cms\Orm\Auth\Role\QueryField orFieldCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query orderAscCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query orderDescCmsAuthId()
 * @method \Cms\Orm\Auth\Role\Query groupByCmsAuthId()
 * @method \Cms\Orm\Auth\Role\QueryField whereCmsRoleId()
 * @method \Cms\Orm\Auth\Role\QueryField andFieldCmsRoleId()
 * @method \Cms\Orm\Auth\Role\QueryField orFieldCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query orderAscCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query orderDescCmsRoleId()
 * @method \Cms\Orm\Auth\Role\Query groupByCmsRoleId()
 * @method \Cms\Orm\Auth\Role\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Auth\Role\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Auth\Role\QueryJoin joinLeft($tableName, $targetTableName = null)
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
