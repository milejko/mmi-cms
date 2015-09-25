<?php

namespace Cms\Orm\Log;

//<editor-fold defaultstate="collapsed" desc="cms_log Query">
/**
 * @method \Cms\Orm\Log\Query limit($limit = null)
 * @method \Cms\Orm\Log\Query offset($offset = null)
 * @method \Cms\Orm\Log\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Log\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Log\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Log\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Log\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Log\Query resetOrder()
 * @method \Cms\Orm\Log\Query resetWhere()
 * @method \Cms\Orm\Log\QueryField whereId()
 * @method \Cms\Orm\Log\QueryField andFieldId()
 * @method \Cms\Orm\Log\QueryField orFieldId()
 * @method \Cms\Orm\Log\Query orderAscId()
 * @method \Cms\Orm\Log\Query orderDescId()
 * @method \Cms\Orm\Log\Query groupById()
 * @method \Cms\Orm\Log\QueryField whereUrl()
 * @method \Cms\Orm\Log\QueryField andFieldUrl()
 * @method \Cms\Orm\Log\QueryField orFieldUrl()
 * @method \Cms\Orm\Log\Query orderAscUrl()
 * @method \Cms\Orm\Log\Query orderDescUrl()
 * @method \Cms\Orm\Log\Query groupByUrl()
 * @method \Cms\Orm\Log\QueryField whereIp()
 * @method \Cms\Orm\Log\QueryField andFieldIp()
 * @method \Cms\Orm\Log\QueryField orFieldIp()
 * @method \Cms\Orm\Log\Query orderAscIp()
 * @method \Cms\Orm\Log\Query orderDescIp()
 * @method \Cms\Orm\Log\Query groupByIp()
 * @method \Cms\Orm\Log\QueryField whereBrowser()
 * @method \Cms\Orm\Log\QueryField andFieldBrowser()
 * @method \Cms\Orm\Log\QueryField orFieldBrowser()
 * @method \Cms\Orm\Log\Query orderAscBrowser()
 * @method \Cms\Orm\Log\Query orderDescBrowser()
 * @method \Cms\Orm\Log\Query groupByBrowser()
 * @method \Cms\Orm\Log\QueryField whereOperation()
 * @method \Cms\Orm\Log\QueryField andFieldOperation()
 * @method \Cms\Orm\Log\QueryField orFieldOperation()
 * @method \Cms\Orm\Log\Query orderAscOperation()
 * @method \Cms\Orm\Log\Query orderDescOperation()
 * @method \Cms\Orm\Log\Query groupByOperation()
 * @method \Cms\Orm\Log\QueryField whereObject()
 * @method \Cms\Orm\Log\QueryField andFieldObject()
 * @method \Cms\Orm\Log\QueryField orFieldObject()
 * @method \Cms\Orm\Log\Query orderAscObject()
 * @method \Cms\Orm\Log\Query orderDescObject()
 * @method \Cms\Orm\Log\Query groupByObject()
 * @method \Cms\Orm\Log\QueryField whereObjectId()
 * @method \Cms\Orm\Log\QueryField andFieldObjectId()
 * @method \Cms\Orm\Log\QueryField orFieldObjectId()
 * @method \Cms\Orm\Log\Query orderAscObjectId()
 * @method \Cms\Orm\Log\Query orderDescObjectId()
 * @method \Cms\Orm\Log\Query groupByObjectId()
 * @method \Cms\Orm\Log\QueryField whereData()
 * @method \Cms\Orm\Log\QueryField andFieldData()
 * @method \Cms\Orm\Log\QueryField orFieldData()
 * @method \Cms\Orm\Log\Query orderAscData()
 * @method \Cms\Orm\Log\Query orderDescData()
 * @method \Cms\Orm\Log\Query groupByData()
 * @method \Cms\Orm\Log\QueryField whereSuccess()
 * @method \Cms\Orm\Log\QueryField andFieldSuccess()
 * @method \Cms\Orm\Log\QueryField orFieldSuccess()
 * @method \Cms\Orm\Log\Query orderAscSuccess()
 * @method \Cms\Orm\Log\Query orderDescSuccess()
 * @method \Cms\Orm\Log\Query groupBySuccess()
 * @method \Cms\Orm\Log\QueryField whereCmsAuthId()
 * @method \Cms\Orm\Log\QueryField andFieldCmsAuthId()
 * @method \Cms\Orm\Log\QueryField orFieldCmsAuthId()
 * @method \Cms\Orm\Log\Query orderAscCmsAuthId()
 * @method \Cms\Orm\Log\Query orderDescCmsAuthId()
 * @method \Cms\Orm\Log\Query groupByCmsAuthId()
 * @method \Cms\Orm\Log\QueryField whereDateTime()
 * @method \Cms\Orm\Log\QueryField andFieldDateTime()
 * @method \Cms\Orm\Log\QueryField orFieldDateTime()
 * @method \Cms\Orm\Log\Query orderAscDateTime()
 * @method \Cms\Orm\Log\Query orderDescDateTime()
 * @method \Cms\Orm\Log\Query groupByDateTime()
 * @method \Cms\Orm\Log\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Log\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Log\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Log\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Log\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Log\Record[] find()
 * @method \Cms\Orm\Log\Record findFirst()
 * @method \Cms\Orm\Log\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_log';

	/**
	 * @return \Cms\Orm\Log\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
