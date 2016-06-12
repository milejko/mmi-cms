<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsRouteQuery">
/**
 * @method CmsRouteQuery limit($limit = null)
 * @method CmsRouteQuery offset($offset = null)
 * @method CmsRouteQuery orderAsc($fieldName, $tableName = null)
 * @method CmsRouteQuery orderDesc($fieldName, $tableName = null)
 * @method CmsRouteQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsRouteQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsRouteQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsRouteQuery resetOrder()
 * @method CmsRouteQuery resetWhere()
 * @method QueryHelper\CmsRouteQueryField whereId()
 * @method QueryHelper\CmsRouteQueryField andFieldId()
 * @method QueryHelper\CmsRouteQueryField orFieldId()
 * @method CmsRouteQuery orderAscId()
 * @method CmsRouteQuery orderDescId()
 * @method CmsRouteQuery groupById()
 * @method QueryHelper\CmsRouteQueryField wherePattern()
 * @method QueryHelper\CmsRouteQueryField andFieldPattern()
 * @method QueryHelper\CmsRouteQueryField orFieldPattern()
 * @method CmsRouteQuery orderAscPattern()
 * @method CmsRouteQuery orderDescPattern()
 * @method CmsRouteQuery groupByPattern()
 * @method QueryHelper\CmsRouteQueryField whereReplace()
 * @method QueryHelper\CmsRouteQueryField andFieldReplace()
 * @method QueryHelper\CmsRouteQueryField orFieldReplace()
 * @method CmsRouteQuery orderAscReplace()
 * @method CmsRouteQuery orderDescReplace()
 * @method CmsRouteQuery groupByReplace()
 * @method QueryHelper\CmsRouteQueryField whereDefault()
 * @method QueryHelper\CmsRouteQueryField andFieldDefault()
 * @method QueryHelper\CmsRouteQueryField orFieldDefault()
 * @method CmsRouteQuery orderAscDefault()
 * @method CmsRouteQuery orderDescDefault()
 * @method CmsRouteQuery groupByDefault()
 * @method QueryHelper\CmsRouteQueryField whereOrder()
 * @method QueryHelper\CmsRouteQueryField andFieldOrder()
 * @method QueryHelper\CmsRouteQueryField orFieldOrder()
 * @method CmsRouteQuery orderAscOrder()
 * @method CmsRouteQuery orderDescOrder()
 * @method CmsRouteQuery groupByOrder()
 * @method QueryHelper\CmsRouteQueryField whereActive()
 * @method QueryHelper\CmsRouteQueryField andFieldActive()
 * @method QueryHelper\CmsRouteQueryField orFieldActive()
 * @method CmsRouteQuery orderAscActive()
 * @method CmsRouteQuery orderDescActive()
 * @method CmsRouteQuery groupByActive()
 * @method QueryHelper\CmsRouteQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsRouteQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsRouteQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsRouteQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsRouteQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsRouteRecord[] find()
 * @method CmsRouteRecord findFirst()
 * @method CmsRouteRecord findPk($value)
 */
//</editor-fold>
class CmsRouteQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_route';

	/**
	 * Aktywne
	 * @return CmsRouteQuery
	 */
	public static function active() {
		return (new self)
				->whereActive()->equals(1)
				->orderAscOrder();
	}

}
