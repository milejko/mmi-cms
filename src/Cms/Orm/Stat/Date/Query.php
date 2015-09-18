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
 * @method \Cms\Orm\Stat\Date\Query\Field whereId()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldId()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldId()
 * @method \Cms\Orm\Stat\Date\Query orderAscId()
 * @method \Cms\Orm\Stat\Date\Query orderDescId()
 * @method \Cms\Orm\Stat\Date\Query groupById()
 * @method \Cms\Orm\Stat\Date\Query\Field whereHour()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldHour()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldHour()
 * @method \Cms\Orm\Stat\Date\Query orderAscHour()
 * @method \Cms\Orm\Stat\Date\Query orderDescHour()
 * @method \Cms\Orm\Stat\Date\Query groupByHour()
 * @method \Cms\Orm\Stat\Date\Query\Field whereDay()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldDay()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldDay()
 * @method \Cms\Orm\Stat\Date\Query orderAscDay()
 * @method \Cms\Orm\Stat\Date\Query orderDescDay()
 * @method \Cms\Orm\Stat\Date\Query groupByDay()
 * @method \Cms\Orm\Stat\Date\Query\Field whereMonth()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldMonth()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldMonth()
 * @method \Cms\Orm\Stat\Date\Query orderAscMonth()
 * @method \Cms\Orm\Stat\Date\Query orderDescMonth()
 * @method \Cms\Orm\Stat\Date\Query groupByMonth()
 * @method \Cms\Orm\Stat\Date\Query\Field whereYear()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldYear()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldYear()
 * @method \Cms\Orm\Stat\Date\Query orderAscYear()
 * @method \Cms\Orm\Stat\Date\Query orderDescYear()
 * @method \Cms\Orm\Stat\Date\Query groupByYear()
 * @method \Cms\Orm\Stat\Date\Query\Field whereObject()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldObject()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldObject()
 * @method \Cms\Orm\Stat\Date\Query orderAscObject()
 * @method \Cms\Orm\Stat\Date\Query orderDescObject()
 * @method \Cms\Orm\Stat\Date\Query groupByObject()
 * @method \Cms\Orm\Stat\Date\Query\Field whereObjectId()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldObjectId()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldObjectId()
 * @method \Cms\Orm\Stat\Date\Query orderAscObjectId()
 * @method \Cms\Orm\Stat\Date\Query orderDescObjectId()
 * @method \Cms\Orm\Stat\Date\Query groupByObjectId()
 * @method \Cms\Orm\Stat\Date\Query\Field whereCount()
 * @method \Cms\Orm\Stat\Date\Query\Field andFieldCount()
 * @method \Cms\Orm\Stat\Date\Query\Field orFieldCount()
 * @method \Cms\Orm\Stat\Date\Query orderAscCount()
 * @method \Cms\Orm\Stat\Date\Query orderDescCount()
 * @method \Cms\Orm\Stat\Date\Query groupByCount()
 * @method \Cms\Orm\Stat\Date\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Date\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Stat\Date\Query\Join joinLeft($tableName, $targetTableName = null)
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
