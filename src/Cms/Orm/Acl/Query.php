<?php

namespace Cms\Orm\Acl;

//<editor-fold defaultstate="collapsed" desc="cms_acl Query">
/**
 * @method \Cms\Orm\Acl\Query limit($limit = null)
 * @method \Cms\Orm\Acl\Query offset($offset = null)
 * @method \Cms\Orm\Acl\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Acl\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Acl\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Acl\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Acl\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Acl\Query resetOrder()
 * @method \Cms\Orm\Acl\Query resetWhere()
 * @method \Cms\Orm\Acl\Query\Field whereId()
 * @method \Cms\Orm\Acl\Query\Field andFieldId()
 * @method \Cms\Orm\Acl\Query\Field orFieldId()
 * @method \Cms\Orm\Acl\Query orderAscId()
 * @method \Cms\Orm\Acl\Query orderDescId()
 * @method \Cms\Orm\Acl\Query groupById()
 * @method \Cms\Orm\Acl\Query\Field whereCmsRoleId()
 * @method \Cms\Orm\Acl\Query\Field andFieldCmsRoleId()
 * @method \Cms\Orm\Acl\Query\Field orFieldCmsRoleId()
 * @method \Cms\Orm\Acl\Query orderAscCmsRoleId()
 * @method \Cms\Orm\Acl\Query orderDescCmsRoleId()
 * @method \Cms\Orm\Acl\Query groupByCmsRoleId()
 * @method \Cms\Orm\Acl\Query\Field whereModule()
 * @method \Cms\Orm\Acl\Query\Field andFieldModule()
 * @method \Cms\Orm\Acl\Query\Field orFieldModule()
 * @method \Cms\Orm\Acl\Query orderAscModule()
 * @method \Cms\Orm\Acl\Query orderDescModule()
 * @method \Cms\Orm\Acl\Query groupByModule()
 * @method \Cms\Orm\Acl\Query\Field whereController()
 * @method \Cms\Orm\Acl\Query\Field andFieldController()
 * @method \Cms\Orm\Acl\Query\Field orFieldController()
 * @method \Cms\Orm\Acl\Query orderAscController()
 * @method \Cms\Orm\Acl\Query orderDescController()
 * @method \Cms\Orm\Acl\Query groupByController()
 * @method \Cms\Orm\Acl\Query\Field whereAction()
 * @method \Cms\Orm\Acl\Query\Field andFieldAction()
 * @method \Cms\Orm\Acl\Query\Field orFieldAction()
 * @method \Cms\Orm\Acl\Query orderAscAction()
 * @method \Cms\Orm\Acl\Query orderDescAction()
 * @method \Cms\Orm\Acl\Query groupByAction()
 * @method \Cms\Orm\Acl\Query\Field whereAccess()
 * @method \Cms\Orm\Acl\Query\Field andFieldAccess()
 * @method \Cms\Orm\Acl\Query\Field orFieldAccess()
 * @method \Cms\Orm\Acl\Query orderAscAccess()
 * @method \Cms\Orm\Acl\Query orderDescAccess()
 * @method \Cms\Orm\Acl\Query groupByAccess()
 * @method \Cms\Orm\Acl\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Acl\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Acl\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Acl\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Acl\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Acl\Record[] find()
 * @method \Cms\Orm\Acl\Record findFirst()
 * @method \Cms\Orm\Acl\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {
	
	protected $_tableName = 'cms_acl';

	/**
	 * @return \Cms\Orm\Acl\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
