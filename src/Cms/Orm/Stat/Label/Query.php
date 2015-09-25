<?php

namespace Cms\Orm\Stat\Label;

//<editor-fold defaultstate="collapsed" desc="cms_stat_label Query">
/**
 * @method \Cms\Orm\Stat\Label\Query limit($limit = null)
 * @method \Cms\Orm\Stat\Label\Query offset($offset = null)
 * @method \Cms\Orm\Stat\Label\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Label\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Label\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Label\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Label\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Label\Query resetOrder()
 * @method \Cms\Orm\Stat\Label\Query resetWhere()
 * @method \Cms\Orm\Stat\Label\QueryField whereId()
 * @method \Cms\Orm\Stat\Label\QueryField andFieldId()
 * @method \Cms\Orm\Stat\Label\QueryField orFieldId()
 * @method \Cms\Orm\Stat\Label\Query orderAscId()
 * @method \Cms\Orm\Stat\Label\Query orderDescId()
 * @method \Cms\Orm\Stat\Label\Query groupById()
 * @method \Cms\Orm\Stat\Label\QueryField whereLang()
 * @method \Cms\Orm\Stat\Label\QueryField andFieldLang()
 * @method \Cms\Orm\Stat\Label\QueryField orFieldLang()
 * @method \Cms\Orm\Stat\Label\Query orderAscLang()
 * @method \Cms\Orm\Stat\Label\Query orderDescLang()
 * @method \Cms\Orm\Stat\Label\Query groupByLang()
 * @method \Cms\Orm\Stat\Label\QueryField whereObject()
 * @method \Cms\Orm\Stat\Label\QueryField andFieldObject()
 * @method \Cms\Orm\Stat\Label\QueryField orFieldObject()
 * @method \Cms\Orm\Stat\Label\Query orderAscObject()
 * @method \Cms\Orm\Stat\Label\Query orderDescObject()
 * @method \Cms\Orm\Stat\Label\Query groupByObject()
 * @method \Cms\Orm\Stat\Label\QueryField whereLabel()
 * @method \Cms\Orm\Stat\Label\QueryField andFieldLabel()
 * @method \Cms\Orm\Stat\Label\QueryField orFieldLabel()
 * @method \Cms\Orm\Stat\Label\Query orderAscLabel()
 * @method \Cms\Orm\Stat\Label\Query orderDescLabel()
 * @method \Cms\Orm\Stat\Label\Query groupByLabel()
 * @method \Cms\Orm\Stat\Label\QueryField whereDescription()
 * @method \Cms\Orm\Stat\Label\QueryField andFieldDescription()
 * @method \Cms\Orm\Stat\Label\QueryField orFieldDescription()
 * @method \Cms\Orm\Stat\Label\Query orderAscDescription()
 * @method \Cms\Orm\Stat\Label\Query orderDescDescription()
 * @method \Cms\Orm\Stat\Label\Query groupByDescription()
 * @method \Cms\Orm\Stat\Label\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Label\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Label\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Label\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Stat\Label\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Stat\Label\Record[] find()
 * @method \Cms\Orm\Stat\Label\Record findFirst()
 * @method \Cms\Orm\Stat\Label\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_stat_label';

	/**
	 * @return \Cms\Orm\Stat\Label\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @param string $object
	 * @return \Cms\Orm\Stat\Label\Query
	 */
	public static function byObject($object) {
		return self::factory()
				->whereObject()->equals($object);
	}

}
