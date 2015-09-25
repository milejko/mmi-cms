<?php

namespace Cms\Orm\Stat\Date;

//<editor-fold defaultstate="collapsed" desc="cms_stat_date Query">
/**
 * @method \Cms\Orm\Stat\Date\Query limit($limit = null)
 * @method \Cms\Orm\Stat\Date\Query offset($offset = null)
 * @method \Cms\Orm\Stat\Date\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Date\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Date\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Date\Query resetOrder()
 * @method \Cms\Orm\Stat\Date\Query resetWhere()
 * @method \Cms\Orm\Stat\Date\QueryField whereId()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldId()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldId()
 * @method \Cms\Orm\Stat\Date\Query orderAscId()
 * @method \Cms\Orm\Stat\Date\Query orderDescId()
 * @method \Cms\Orm\Stat\Date\Query groupById()
 * @method \Cms\Orm\Stat\Date\QueryField whereHour()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldHour()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldHour()
 * @method \Cms\Orm\Stat\Date\Query orderAscHour()
 * @method \Cms\Orm\Stat\Date\Query orderDescHour()
 * @method \Cms\Orm\Stat\Date\Query groupByHour()
 * @method \Cms\Orm\Stat\Date\QueryField whereDay()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldDay()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldDay()
 * @method \Cms\Orm\Stat\Date\Query orderAscDay()
 * @method \Cms\Orm\Stat\Date\Query orderDescDay()
 * @method \Cms\Orm\Stat\Date\Query groupByDay()
 * @method \Cms\Orm\Stat\Date\QueryField whereMonth()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldMonth()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldMonth()
 * @method \Cms\Orm\Stat\Date\Query orderAscMonth()
 * @method \Cms\Orm\Stat\Date\Query orderDescMonth()
 * @method \Cms\Orm\Stat\Date\Query groupByMonth()
 * @method \Cms\Orm\Stat\Date\QueryField whereYear()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldYear()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldYear()
 * @method \Cms\Orm\Stat\Date\Query orderAscYear()
 * @method \Cms\Orm\Stat\Date\Query orderDescYear()
 * @method \Cms\Orm\Stat\Date\Query groupByYear()
 * @method \Cms\Orm\Stat\Date\QueryField whereObject()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldObject()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldObject()
 * @method \Cms\Orm\Stat\Date\Query orderAscObject()
 * @method \Cms\Orm\Stat\Date\Query orderDescObject()
 * @method \Cms\Orm\Stat\Date\Query groupByObject()
 * @method \Cms\Orm\Stat\Date\QueryField whereObjectId()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldObjectId()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldObjectId()
 * @method \Cms\Orm\Stat\Date\Query orderAscObjectId()
 * @method \Cms\Orm\Stat\Date\Query orderDescObjectId()
 * @method \Cms\Orm\Stat\Date\Query groupByObjectId()
 * @method \Cms\Orm\Stat\Date\QueryField whereCount()
 * @method \Cms\Orm\Stat\Date\QueryField andFieldCount()
 * @method \Cms\Orm\Stat\Date\QueryField orFieldCount()
 * @method \Cms\Orm\Stat\Date\Query orderAscCount()
 * @method \Cms\Orm\Stat\Date\Query orderDescCount()
 * @method \Cms\Orm\Stat\Date\Query groupByCount()
 * @method \Cms\Orm\Stat\Date\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Stat\Date\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Stat\Date\Record[] find()
 * @method \Cms\Orm\Stat\Date\Record findFirst()
 * @method \Cms\Orm\Stat\Date\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_stat_date';

	/**
	 * @return \Cms\Orm\Stat\Date\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
