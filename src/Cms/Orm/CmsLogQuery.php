<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsLogQuery">
/**
 * @method CmsLogQuery limit($limit = null)
 * @method CmsLogQuery offset($offset = null)
 * @method CmsLogQuery orderAsc($fieldName, $tableName = null)
 * @method CmsLogQuery orderDesc($fieldName, $tableName = null)
 * @method CmsLogQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsLogQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsLogQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsLogQuery resetOrder()
 * @method CmsLogQuery resetWhere()
 * @method QueryHelper\CmsLogQueryField whereId()
 * @method QueryHelper\CmsLogQueryField andFieldId()
 * @method QueryHelper\CmsLogQueryField orFieldId()
 * @method CmsLogQuery orderAscId()
 * @method CmsLogQuery orderDescId()
 * @method CmsLogQuery groupById()
 * @method QueryHelper\CmsLogQueryField whereUrl()
 * @method QueryHelper\CmsLogQueryField andFieldUrl()
 * @method QueryHelper\CmsLogQueryField orFieldUrl()
 * @method CmsLogQuery orderAscUrl()
 * @method CmsLogQuery orderDescUrl()
 * @method CmsLogQuery groupByUrl()
 * @method QueryHelper\CmsLogQueryField whereIp()
 * @method QueryHelper\CmsLogQueryField andFieldIp()
 * @method QueryHelper\CmsLogQueryField orFieldIp()
 * @method CmsLogQuery orderAscIp()
 * @method CmsLogQuery orderDescIp()
 * @method CmsLogQuery groupByIp()
 * @method QueryHelper\CmsLogQueryField whereBrowser()
 * @method QueryHelper\CmsLogQueryField andFieldBrowser()
 * @method QueryHelper\CmsLogQueryField orFieldBrowser()
 * @method CmsLogQuery orderAscBrowser()
 * @method CmsLogQuery orderDescBrowser()
 * @method CmsLogQuery groupByBrowser()
 * @method QueryHelper\CmsLogQueryField whereOperation()
 * @method QueryHelper\CmsLogQueryField andFieldOperation()
 * @method QueryHelper\CmsLogQueryField orFieldOperation()
 * @method CmsLogQuery orderAscOperation()
 * @method CmsLogQuery orderDescOperation()
 * @method CmsLogQuery groupByOperation()
 * @method QueryHelper\CmsLogQueryField whereObject()
 * @method QueryHelper\CmsLogQueryField andFieldObject()
 * @method QueryHelper\CmsLogQueryField orFieldObject()
 * @method CmsLogQuery orderAscObject()
 * @method CmsLogQuery orderDescObject()
 * @method CmsLogQuery groupByObject()
 * @method QueryHelper\CmsLogQueryField whereObjectId()
 * @method QueryHelper\CmsLogQueryField andFieldObjectId()
 * @method QueryHelper\CmsLogQueryField orFieldObjectId()
 * @method CmsLogQuery orderAscObjectId()
 * @method CmsLogQuery orderDescObjectId()
 * @method CmsLogQuery groupByObjectId()
 * @method QueryHelper\CmsLogQueryField whereData()
 * @method QueryHelper\CmsLogQueryField andFieldData()
 * @method QueryHelper\CmsLogQueryField orFieldData()
 * @method CmsLogQuery orderAscData()
 * @method CmsLogQuery orderDescData()
 * @method CmsLogQuery groupByData()
 * @method QueryHelper\CmsLogQueryField whereSuccess()
 * @method QueryHelper\CmsLogQueryField andFieldSuccess()
 * @method QueryHelper\CmsLogQueryField orFieldSuccess()
 * @method CmsLogQuery orderAscSuccess()
 * @method CmsLogQuery orderDescSuccess()
 * @method CmsLogQuery groupBySuccess()
 * @method QueryHelper\CmsLogQueryField whereCmsAuthId()
 * @method QueryHelper\CmsLogQueryField andFieldCmsAuthId()
 * @method QueryHelper\CmsLogQueryField orFieldCmsAuthId()
 * @method CmsLogQuery orderAscCmsAuthId()
 * @method CmsLogQuery orderDescCmsAuthId()
 * @method CmsLogQuery groupByCmsAuthId()
 * @method QueryHelper\CmsLogQueryField whereDateTime()
 * @method QueryHelper\CmsLogQueryField andFieldDateTime()
 * @method QueryHelper\CmsLogQueryField orFieldDateTime()
 * @method CmsLogQuery orderAscDateTime()
 * @method CmsLogQuery orderDescDateTime()
 * @method CmsLogQuery groupByDateTime()
 * @method QueryHelper\CmsLogQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsLogQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsLogQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsLogQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsLogQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsLogRecord[] find()
 * @method CmsLogRecord findFirst()
 * @method CmsLogRecord findPk($value)
 */
//</editor-fold>
class CmsLogQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_log';

	/**
	 * @return CmsLogQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
