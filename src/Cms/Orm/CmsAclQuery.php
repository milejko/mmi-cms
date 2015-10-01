<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsAclQuery">
/**
 * @method CmsAclQuery limit($limit = null)
 * @method CmsAclQuery offset($offset = null)
 * @method CmsAclQuery orderAsc($fieldName, $tableName = null)
 * @method CmsAclQuery orderDesc($fieldName, $tableName = null)
 * @method CmsAclQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsAclQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsAclQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsAclQuery resetOrder()
 * @method CmsAclQuery resetWhere()
 * @method QueryHelper\CmsAclQueryField whereId()
 * @method QueryHelper\CmsAclQueryField andFieldId()
 * @method QueryHelper\CmsAclQueryField orFieldId()
 * @method CmsAclQuery orderAscId()
 * @method CmsAclQuery orderDescId()
 * @method CmsAclQuery groupById()
 * @method QueryHelper\CmsAclQueryField whereCmsRoleId()
 * @method QueryHelper\CmsAclQueryField andFieldCmsRoleId()
 * @method QueryHelper\CmsAclQueryField orFieldCmsRoleId()
 * @method CmsAclQuery orderAscCmsRoleId()
 * @method CmsAclQuery orderDescCmsRoleId()
 * @method CmsAclQuery groupByCmsRoleId()
 * @method QueryHelper\CmsAclQueryField whereModule()
 * @method QueryHelper\CmsAclQueryField andFieldModule()
 * @method QueryHelper\CmsAclQueryField orFieldModule()
 * @method CmsAclQuery orderAscModule()
 * @method CmsAclQuery orderDescModule()
 * @method CmsAclQuery groupByModule()
 * @method QueryHelper\CmsAclQueryField whereController()
 * @method QueryHelper\CmsAclQueryField andFieldController()
 * @method QueryHelper\CmsAclQueryField orFieldController()
 * @method CmsAclQuery orderAscController()
 * @method CmsAclQuery orderDescController()
 * @method CmsAclQuery groupByController()
 * @method QueryHelper\CmsAclQueryField whereAction()
 * @method QueryHelper\CmsAclQueryField andFieldAction()
 * @method QueryHelper\CmsAclQueryField orFieldAction()
 * @method CmsAclQuery orderAscAction()
 * @method CmsAclQuery orderDescAction()
 * @method CmsAclQuery groupByAction()
 * @method QueryHelper\CmsAclQueryField whereAccess()
 * @method QueryHelper\CmsAclQueryField andFieldAccess()
 * @method QueryHelper\CmsAclQueryField orFieldAccess()
 * @method CmsAclQuery orderAscAccess()
 * @method CmsAclQuery orderDescAccess()
 * @method CmsAclQuery groupByAccess()
 * @method QueryHelper\CmsAclQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAclQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsAclQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAclQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsAclQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsAclRecord[] find()
 * @method CmsAclRecord findFirst()
 * @method CmsAclRecord findPk($value)
 */
//</editor-fold>
class CmsAclQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_acl';

	/**
	 * @return CmsAclQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
