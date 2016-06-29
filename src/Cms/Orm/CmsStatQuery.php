<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsStatQuery">
/**
 * @method CmsStatQuery limit($limit = null)
 * @method CmsStatQuery offset($offset = null)
 * @method CmsStatQuery orderAsc($fieldName, $tableName = null)
 * @method CmsStatQuery orderDesc($fieldName, $tableName = null)
 * @method CmsStatQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsStatQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsStatQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsStatQuery resetOrder()
 * @method CmsStatQuery resetWhere()
 * @method QueryHelper\CmsStatQueryField whereId()
 * @method QueryHelper\CmsStatQueryField andFieldId()
 * @method QueryHelper\CmsStatQueryField orFieldId()
 * @method CmsStatQuery orderAscId()
 * @method CmsStatQuery orderDescId()
 * @method CmsStatQuery groupById()
 * @method QueryHelper\CmsStatQueryField whereObject()
 * @method QueryHelper\CmsStatQueryField andFieldObject()
 * @method QueryHelper\CmsStatQueryField orFieldObject()
 * @method CmsStatQuery orderAscObject()
 * @method CmsStatQuery orderDescObject()
 * @method CmsStatQuery groupByObject()
 * @method QueryHelper\CmsStatQueryField whereObjectId()
 * @method QueryHelper\CmsStatQueryField andFieldObjectId()
 * @method QueryHelper\CmsStatQueryField orFieldObjectId()
 * @method CmsStatQuery orderAscObjectId()
 * @method CmsStatQuery orderDescObjectId()
 * @method CmsStatQuery groupByObjectId()
 * @method QueryHelper\CmsStatQueryField whereDateTime()
 * @method QueryHelper\CmsStatQueryField andFieldDateTime()
 * @method QueryHelper\CmsStatQueryField orFieldDateTime()
 * @method CmsStatQuery orderAscDateTime()
 * @method CmsStatQuery orderDescDateTime()
 * @method CmsStatQuery groupByDateTime()
 * @method QueryHelper\CmsStatQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsStatQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsStatQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsStatQueryJoin join($tableName, $targetTableName = null, $alias = null)
 * @method QueryHelper\CmsStatQueryJoin joinLeft($tableName, $targetTableName = null, $alias = null)
 * @method CmsStatRecord[] find()
 * @method CmsStatRecord findFirst()
 * @method CmsStatRecord findPk($value)
 */
//</editor-fold>
class CmsStatQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_stat';

}
