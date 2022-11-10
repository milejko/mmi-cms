<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsAuthRoleQuery">
/**
 * @method CmsAuthRoleQuery limit($limit = null)
 * @method CmsAuthRoleQuery offset($offset = null)
 * @method CmsAuthRoleQuery orderAsc($fieldName, $tableName = null)
 * @method CmsAuthRoleQuery orderDesc($fieldName, $tableName = null)
 * @method CmsAuthRoleQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsAuthRoleQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsAuthRoleQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsAuthRoleQuery resetOrder()
 * @method CmsAuthRoleQuery resetWhere()
 * @method QueryHelper\CmsAuthRoleQueryField whereId()
 * @method QueryHelper\CmsAuthRoleQueryField andFieldId()
 * @method QueryHelper\CmsAuthRoleQueryField orFieldId()
 * @method CmsAuthRoleQuery orderAscId()
 * @method CmsAuthRoleQuery orderDescId()
 * @method CmsAuthRoleQuery groupById()
 * @method QueryHelper\CmsAuthRoleQueryField whereCmsAuthId()
 * @method QueryHelper\CmsAuthRoleQueryField andFieldCmsAuthId()
 * @method QueryHelper\CmsAuthRoleQueryField orFieldCmsAuthId()
 * @method CmsAuthRoleQuery orderAscCmsAuthId()
 * @method CmsAuthRoleQuery orderDescCmsAuthId()
 * @method CmsAuthRoleQuery groupByCmsAuthId()
 * @method QueryHelper\CmsAuthRoleQueryField whereCmsRoleId()
 * @method QueryHelper\CmsAuthRoleQueryField andFieldCmsRoleId()
 * @method QueryHelper\CmsAuthRoleQueryField orFieldCmsRoleId()
 * @method CmsAuthRoleQuery orderAscCmsRoleId()
 * @method CmsAuthRoleQuery orderDescCmsRoleId()
 * @method CmsAuthRoleQuery groupByCmsRoleId()
 * @method QueryHelper\CmsAuthRoleQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAuthRoleQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsAuthRoleQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsAuthRoleQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsAuthRoleQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsAuthRoleRecord[] find()
 * @method CmsAuthRoleRecord findFirst()
 * @method CmsAuthRoleRecord findPk($value)
 */
//</editor-fold>
class CmsAuthRoleQuery extends \Mmi\Orm\Query
{
    protected $_tableName = 'cms_auth_role';

    /**
     * Zapytanie po authId
     * @param integer $authId
     * @return CmsAuthRoleQuery
     */
    public static function byAuthId($authId)
    {
        return (new self())
                ->whereCmsAuthId()->equals($authId);
    }

    /**
     * Zapytanie po authId z połączoną rolą
     * @param integer $authId
     * @return CmsAuthRoleQuery
     */
    public static function joinedRoleByAuthId($authId)
    {
        return self::byAuthId($authId)
                ->join('cms_role')->on('cms_role_id');
    }
}
