<?php

namespace Cms\Orm\Cron;

//<editor-fold defaultstate="collapsed" desc="cms_cron Query">
/**
 * @method \Cms\Orm\Cron\Query limit($limit = null)
 * @method \Cms\Orm\Cron\Query offset($offset = null)
 * @method \Cms\Orm\Cron\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Cron\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Cron\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Cron\Query resetOrder()
 * @method \Cms\Orm\Cron\Query resetWhere()
 * @method \Cms\Orm\Cron\Query\Field whereId()
 * @method \Cms\Orm\Cron\Query\Field andFieldId()
 * @method \Cms\Orm\Cron\Query\Field orFieldId()
 * @method \Cms\Orm\Cron\Query orderAscId()
 * @method \Cms\Orm\Cron\Query orderDescId()
 * @method \Cms\Orm\Cron\Query groupById()
 * @method \Cms\Orm\Cron\Query\Field whereActive()
 * @method \Cms\Orm\Cron\Query\Field andFieldActive()
 * @method \Cms\Orm\Cron\Query\Field orFieldActive()
 * @method \Cms\Orm\Cron\Query orderAscActive()
 * @method \Cms\Orm\Cron\Query orderDescActive()
 * @method \Cms\Orm\Cron\Query groupByActive()
 * @method \Cms\Orm\Cron\Query\Field whereMinute()
 * @method \Cms\Orm\Cron\Query\Field andFieldMinute()
 * @method \Cms\Orm\Cron\Query\Field orFieldMinute()
 * @method \Cms\Orm\Cron\Query orderAscMinute()
 * @method \Cms\Orm\Cron\Query orderDescMinute()
 * @method \Cms\Orm\Cron\Query groupByMinute()
 * @method \Cms\Orm\Cron\Query\Field whereHour()
 * @method \Cms\Orm\Cron\Query\Field andFieldHour()
 * @method \Cms\Orm\Cron\Query\Field orFieldHour()
 * @method \Cms\Orm\Cron\Query orderAscHour()
 * @method \Cms\Orm\Cron\Query orderDescHour()
 * @method \Cms\Orm\Cron\Query groupByHour()
 * @method \Cms\Orm\Cron\Query\Field whereDayOfMonth()
 * @method \Cms\Orm\Cron\Query\Field andFieldDayOfMonth()
 * @method \Cms\Orm\Cron\Query\Field orFieldDayOfMonth()
 * @method \Cms\Orm\Cron\Query orderAscDayOfMonth()
 * @method \Cms\Orm\Cron\Query orderDescDayOfMonth()
 * @method \Cms\Orm\Cron\Query groupByDayOfMonth()
 * @method \Cms\Orm\Cron\Query\Field whereMonth()
 * @method \Cms\Orm\Cron\Query\Field andFieldMonth()
 * @method \Cms\Orm\Cron\Query\Field orFieldMonth()
 * @method \Cms\Orm\Cron\Query orderAscMonth()
 * @method \Cms\Orm\Cron\Query orderDescMonth()
 * @method \Cms\Orm\Cron\Query groupByMonth()
 * @method \Cms\Orm\Cron\Query\Field whereDayOfWeek()
 * @method \Cms\Orm\Cron\Query\Field andFieldDayOfWeek()
 * @method \Cms\Orm\Cron\Query\Field orFieldDayOfWeek()
 * @method \Cms\Orm\Cron\Query orderAscDayOfWeek()
 * @method \Cms\Orm\Cron\Query orderDescDayOfWeek()
 * @method \Cms\Orm\Cron\Query groupByDayOfWeek()
 * @method \Cms\Orm\Cron\Query\Field whereName()
 * @method \Cms\Orm\Cron\Query\Field andFieldName()
 * @method \Cms\Orm\Cron\Query\Field orFieldName()
 * @method \Cms\Orm\Cron\Query orderAscName()
 * @method \Cms\Orm\Cron\Query orderDescName()
 * @method \Cms\Orm\Cron\Query groupByName()
 * @method \Cms\Orm\Cron\Query\Field whereDescription()
 * @method \Cms\Orm\Cron\Query\Field andFieldDescription()
 * @method \Cms\Orm\Cron\Query\Field orFieldDescription()
 * @method \Cms\Orm\Cron\Query orderAscDescription()
 * @method \Cms\Orm\Cron\Query orderDescDescription()
 * @method \Cms\Orm\Cron\Query groupByDescription()
 * @method \Cms\Orm\Cron\Query\Field whereModule()
 * @method \Cms\Orm\Cron\Query\Field andFieldModule()
 * @method \Cms\Orm\Cron\Query\Field orFieldModule()
 * @method \Cms\Orm\Cron\Query orderAscModule()
 * @method \Cms\Orm\Cron\Query orderDescModule()
 * @method \Cms\Orm\Cron\Query groupByModule()
 * @method \Cms\Orm\Cron\Query\Field whereController()
 * @method \Cms\Orm\Cron\Query\Field andFieldController()
 * @method \Cms\Orm\Cron\Query\Field orFieldController()
 * @method \Cms\Orm\Cron\Query orderAscController()
 * @method \Cms\Orm\Cron\Query orderDescController()
 * @method \Cms\Orm\Cron\Query groupByController()
 * @method \Cms\Orm\Cron\Query\Field whereAction()
 * @method \Cms\Orm\Cron\Query\Field andFieldAction()
 * @method \Cms\Orm\Cron\Query\Field orFieldAction()
 * @method \Cms\Orm\Cron\Query orderAscAction()
 * @method \Cms\Orm\Cron\Query orderDescAction()
 * @method \Cms\Orm\Cron\Query groupByAction()
 * @method \Cms\Orm\Cron\Query\Field whereDateAdd()
 * @method \Cms\Orm\Cron\Query\Field andFieldDateAdd()
 * @method \Cms\Orm\Cron\Query\Field orFieldDateAdd()
 * @method \Cms\Orm\Cron\Query orderAscDateAdd()
 * @method \Cms\Orm\Cron\Query orderDescDateAdd()
 * @method \Cms\Orm\Cron\Query groupByDateAdd()
 * @method \Cms\Orm\Cron\Query\Field whereDateModified()
 * @method \Cms\Orm\Cron\Query\Field andFieldDateModified()
 * @method \Cms\Orm\Cron\Query\Field orFieldDateModified()
 * @method \Cms\Orm\Cron\Query orderAscDateModified()
 * @method \Cms\Orm\Cron\Query orderDescDateModified()
 * @method \Cms\Orm\Cron\Query groupByDateModified()
 * @method \Cms\Orm\Cron\Query\Field whereDateLastExecute()
 * @method \Cms\Orm\Cron\Query\Field andFieldDateLastExecute()
 * @method \Cms\Orm\Cron\Query\Field orFieldDateLastExecute()
 * @method \Cms\Orm\Cron\Query orderAscDateLastExecute()
 * @method \Cms\Orm\Cron\Query orderDescDateLastExecute()
 * @method \Cms\Orm\Cron\Query groupByDateLastExecute()
 * @method \Cms\Orm\Cron\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Cron\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Cron\Record[] find()
 * @method \Cms\Orm\Cron\Record findFirst()
 * @method \Cms\Orm\Cron\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_cron';

	/**
	 * @return \Cms\Orm\Cron\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Zapytanie o aktywne cron'y
	 * @return \Cms\Orm\Cron\Query
	 */
	public static function active() {
		return self::factory()
				->whereActive()->equals(1)
				->orderAscId();
	}

}
