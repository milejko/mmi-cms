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
 * @method \Cms\Orm\Cron\QueryField whereId()
 * @method \Cms\Orm\Cron\QueryField andFieldId()
 * @method \Cms\Orm\Cron\QueryField orFieldId()
 * @method \Cms\Orm\Cron\Query orderAscId()
 * @method \Cms\Orm\Cron\Query orderDescId()
 * @method \Cms\Orm\Cron\Query groupById()
 * @method \Cms\Orm\Cron\QueryField whereActive()
 * @method \Cms\Orm\Cron\QueryField andFieldActive()
 * @method \Cms\Orm\Cron\QueryField orFieldActive()
 * @method \Cms\Orm\Cron\Query orderAscActive()
 * @method \Cms\Orm\Cron\Query orderDescActive()
 * @method \Cms\Orm\Cron\Query groupByActive()
 * @method \Cms\Orm\Cron\QueryField whereMinute()
 * @method \Cms\Orm\Cron\QueryField andFieldMinute()
 * @method \Cms\Orm\Cron\QueryField orFieldMinute()
 * @method \Cms\Orm\Cron\Query orderAscMinute()
 * @method \Cms\Orm\Cron\Query orderDescMinute()
 * @method \Cms\Orm\Cron\Query groupByMinute()
 * @method \Cms\Orm\Cron\QueryField whereHour()
 * @method \Cms\Orm\Cron\QueryField andFieldHour()
 * @method \Cms\Orm\Cron\QueryField orFieldHour()
 * @method \Cms\Orm\Cron\Query orderAscHour()
 * @method \Cms\Orm\Cron\Query orderDescHour()
 * @method \Cms\Orm\Cron\Query groupByHour()
 * @method \Cms\Orm\Cron\QueryField whereDayOfMonth()
 * @method \Cms\Orm\Cron\QueryField andFieldDayOfMonth()
 * @method \Cms\Orm\Cron\QueryField orFieldDayOfMonth()
 * @method \Cms\Orm\Cron\Query orderAscDayOfMonth()
 * @method \Cms\Orm\Cron\Query orderDescDayOfMonth()
 * @method \Cms\Orm\Cron\Query groupByDayOfMonth()
 * @method \Cms\Orm\Cron\QueryField whereMonth()
 * @method \Cms\Orm\Cron\QueryField andFieldMonth()
 * @method \Cms\Orm\Cron\QueryField orFieldMonth()
 * @method \Cms\Orm\Cron\Query orderAscMonth()
 * @method \Cms\Orm\Cron\Query orderDescMonth()
 * @method \Cms\Orm\Cron\Query groupByMonth()
 * @method \Cms\Orm\Cron\QueryField whereDayOfWeek()
 * @method \Cms\Orm\Cron\QueryField andFieldDayOfWeek()
 * @method \Cms\Orm\Cron\QueryField orFieldDayOfWeek()
 * @method \Cms\Orm\Cron\Query orderAscDayOfWeek()
 * @method \Cms\Orm\Cron\Query orderDescDayOfWeek()
 * @method \Cms\Orm\Cron\Query groupByDayOfWeek()
 * @method \Cms\Orm\Cron\QueryField whereName()
 * @method \Cms\Orm\Cron\QueryField andFieldName()
 * @method \Cms\Orm\Cron\QueryField orFieldName()
 * @method \Cms\Orm\Cron\Query orderAscName()
 * @method \Cms\Orm\Cron\Query orderDescName()
 * @method \Cms\Orm\Cron\Query groupByName()
 * @method \Cms\Orm\Cron\QueryField whereDescription()
 * @method \Cms\Orm\Cron\QueryField andFieldDescription()
 * @method \Cms\Orm\Cron\QueryField orFieldDescription()
 * @method \Cms\Orm\Cron\Query orderAscDescription()
 * @method \Cms\Orm\Cron\Query orderDescDescription()
 * @method \Cms\Orm\Cron\Query groupByDescription()
 * @method \Cms\Orm\Cron\QueryField whereModule()
 * @method \Cms\Orm\Cron\QueryField andFieldModule()
 * @method \Cms\Orm\Cron\QueryField orFieldModule()
 * @method \Cms\Orm\Cron\Query orderAscModule()
 * @method \Cms\Orm\Cron\Query orderDescModule()
 * @method \Cms\Orm\Cron\Query groupByModule()
 * @method \Cms\Orm\Cron\QueryField whereController()
 * @method \Cms\Orm\Cron\QueryField andFieldController()
 * @method \Cms\Orm\Cron\QueryField orFieldController()
 * @method \Cms\Orm\Cron\Query orderAscController()
 * @method \Cms\Orm\Cron\Query orderDescController()
 * @method \Cms\Orm\Cron\Query groupByController()
 * @method \Cms\Orm\Cron\QueryField whereAction()
 * @method \Cms\Orm\Cron\QueryField andFieldAction()
 * @method \Cms\Orm\Cron\QueryField orFieldAction()
 * @method \Cms\Orm\Cron\Query orderAscAction()
 * @method \Cms\Orm\Cron\Query orderDescAction()
 * @method \Cms\Orm\Cron\Query groupByAction()
 * @method \Cms\Orm\Cron\QueryField whereDateAdd()
 * @method \Cms\Orm\Cron\QueryField andFieldDateAdd()
 * @method \Cms\Orm\Cron\QueryField orFieldDateAdd()
 * @method \Cms\Orm\Cron\Query orderAscDateAdd()
 * @method \Cms\Orm\Cron\Query orderDescDateAdd()
 * @method \Cms\Orm\Cron\Query groupByDateAdd()
 * @method \Cms\Orm\Cron\QueryField whereDateModified()
 * @method \Cms\Orm\Cron\QueryField andFieldDateModified()
 * @method \Cms\Orm\Cron\QueryField orFieldDateModified()
 * @method \Cms\Orm\Cron\Query orderAscDateModified()
 * @method \Cms\Orm\Cron\Query orderDescDateModified()
 * @method \Cms\Orm\Cron\Query groupByDateModified()
 * @method \Cms\Orm\Cron\QueryField whereDateLastExecute()
 * @method \Cms\Orm\Cron\QueryField andFieldDateLastExecute()
 * @method \Cms\Orm\Cron\QueryField orFieldDateLastExecute()
 * @method \Cms\Orm\Cron\Query orderAscDateLastExecute()
 * @method \Cms\Orm\Cron\Query orderDescDateLastExecute()
 * @method \Cms\Orm\Cron\Query groupByDateLastExecute()
 * @method \Cms\Orm\Cron\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Cron\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Cron\QueryJoin joinLeft($tableName, $targetTableName = null)
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
