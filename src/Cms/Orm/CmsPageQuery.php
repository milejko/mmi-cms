<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsPageQuery">
/**
 * @method CmsPageQuery limit($limit = null)
 * @method CmsPageQuery offset($offset = null)
 * @method CmsPageQuery orderAsc($fieldName, $tableName = null)
 * @method CmsPageQuery orderDesc($fieldName, $tableName = null)
 * @method CmsPageQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsPageQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsPageQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsPageQuery resetOrder()
 * @method CmsPageQuery resetWhere()
 * @method QueryHelper\CmsPageQueryField whereId()
 * @method QueryHelper\CmsPageQueryField andFieldId()
 * @method QueryHelper\CmsPageQueryField orFieldId()
 * @method CmsPageQuery orderAscId()
 * @method CmsPageQuery orderDescId()
 * @method CmsPageQuery groupById()
 * @method QueryHelper\CmsPageQueryField whereName()
 * @method QueryHelper\CmsPageQueryField andFieldName()
 * @method QueryHelper\CmsPageQueryField orFieldName()
 * @method CmsPageQuery orderAscName()
 * @method CmsPageQuery orderDescName()
 * @method CmsPageQuery groupByName()
 * @method QueryHelper\CmsPageQueryField whereCmsNavigationId()
 * @method QueryHelper\CmsPageQueryField andFieldCmsNavigationId()
 * @method QueryHelper\CmsPageQueryField orFieldCmsNavigationId()
 * @method CmsPageQuery orderAscCmsNavigationId()
 * @method CmsPageQuery orderDescCmsNavigationId()
 * @method CmsPageQuery groupByCmsNavigationId()
 * @method QueryHelper\CmsPageQueryField whereCmsRouteId()
 * @method QueryHelper\CmsPageQueryField andFieldCmsRouteId()
 * @method QueryHelper\CmsPageQueryField orFieldCmsRouteId()
 * @method CmsPageQuery orderAscCmsRouteId()
 * @method CmsPageQuery orderDescCmsRouteId()
 * @method CmsPageQuery groupByCmsRouteId()
 * @method QueryHelper\CmsPageQueryField whereText()
 * @method QueryHelper\CmsPageQueryField andFieldText()
 * @method QueryHelper\CmsPageQueryField orFieldText()
 * @method CmsPageQuery orderAscText()
 * @method CmsPageQuery orderDescText()
 * @method CmsPageQuery groupByText()
 * @method QueryHelper\CmsPageQueryField whereActive()
 * @method QueryHelper\CmsPageQueryField andFieldActive()
 * @method QueryHelper\CmsPageQueryField orFieldActive()
 * @method CmsPageQuery orderAscActive()
 * @method CmsPageQuery orderDescActive()
 * @method CmsPageQuery groupByActive()
 * @method QueryHelper\CmsPageQueryField whereDateAdd()
 * @method QueryHelper\CmsPageQueryField andFieldDateAdd()
 * @method QueryHelper\CmsPageQueryField orFieldDateAdd()
 * @method CmsPageQuery orderAscDateAdd()
 * @method CmsPageQuery orderDescDateAdd()
 * @method CmsPageQuery groupByDateAdd()
 * @method QueryHelper\CmsPageQueryField whereDateModify()
 * @method QueryHelper\CmsPageQueryField andFieldDateModify()
 * @method QueryHelper\CmsPageQueryField orFieldDateModify()
 * @method CmsPageQuery orderAscDateModify()
 * @method CmsPageQuery orderDescDateModify()
 * @method CmsPageQuery groupByDateModify()
 * @method QueryHelper\CmsPageQueryField whereCmsAuthId()
 * @method QueryHelper\CmsPageQueryField andFieldCmsAuthId()
 * @method QueryHelper\CmsPageQueryField orFieldCmsAuthId()
 * @method CmsPageQuery orderAscCmsAuthId()
 * @method CmsPageQuery orderDescCmsAuthId()
 * @method CmsPageQuery groupByCmsAuthId()
 * @method QueryHelper\CmsPageQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsPageQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsPageQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsPageQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsPageQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsPageRecord[] find()
 * @method CmsPageRecord findFirst()
 * @method CmsPageRecord findPk($value)
 */
//</editor-fold>
class CmsPageQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_page';

	/**
	 * @return CmsPageQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
