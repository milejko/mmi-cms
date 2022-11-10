<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsRoleQuery">
/**
 * @method CmsRoleQuery limit($limit = null)
 * @method CmsRoleQuery offset($offset = null)
 * @method CmsRoleQuery orderAsc($fieldName, $tableName = null)
 * @method CmsRoleQuery orderDesc($fieldName, $tableName = null)
 * @method CmsRoleQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsRoleQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsRoleQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsRoleQuery resetOrder()
 * @method CmsRoleQuery resetWhere()
 * @method QueryHelper\CmsRoleQueryField whereId()
 * @method QueryHelper\CmsRoleQueryField andFieldId()
 * @method QueryHelper\CmsRoleQueryField orFieldId()
 * @method CmsRoleQuery orderAscId()
 * @method CmsRoleQuery orderDescId()
 * @method CmsRoleQuery groupById()
 * @method QueryHelper\CmsRoleQueryField whereName()
 * @method QueryHelper\CmsRoleQueryField andFieldName()
 * @method QueryHelper\CmsRoleQueryField orFieldName()
 * @method CmsRoleQuery orderAscName()
 * @method CmsRoleQuery orderDescName()
 * @method CmsRoleQuery groupByName()
 * @method QueryHelper\CmsRoleQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsRoleQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsRoleQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsRoleQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsRoleQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsRoleRecord[] find()
 * @method CmsRoleRecord findFirst()
 * @method CmsRoleRecord findPk($value)
 */
//</editor-fold>
class CmsRoleQuery extends \Mmi\Orm\Query
{
    protected $_tableName = 'cms_role';
}
