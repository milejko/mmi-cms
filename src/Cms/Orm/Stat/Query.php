<?php

namespace Cms\Orm\Stat;

//<editor-fold defaultstate="collapsed" desc="cms_stat Query">
/**
 * @method \Cms\Orm\Stat\Query limit($limit = null)
 * @method \Cms\Orm\Stat\Query offset($offset = null)
 * @method \Cms\Orm\Stat\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Stat\Query resetOrder()
 * @method \Cms\Orm\Stat\Query resetWhere()
 * @method \Cms\Orm\Stat\Query\Field whereId()
 * @method \Cms\Orm\Stat\Query\Field andFieldId()
 * @method \Cms\Orm\Stat\Query\Field orFieldId()
 * @method \Cms\Orm\Stat\Query orderAscId()
 * @method \Cms\Orm\Stat\Query orderDescId()
 * @method \Cms\Orm\Stat\Query groupById()
 * @method \Cms\Orm\Stat\Query\Field whereObject()
 * @method \Cms\Orm\Stat\Query\Field andFieldObject()
 * @method \Cms\Orm\Stat\Query\Field orFieldObject()
 * @method \Cms\Orm\Stat\Query orderAscObject()
 * @method \Cms\Orm\Stat\Query orderDescObject()
 * @method \Cms\Orm\Stat\Query groupByObject()
 * @method \Cms\Orm\Stat\Query\Field whereObjectId()
 * @method \Cms\Orm\Stat\Query\Field andFieldObjectId()
 * @method \Cms\Orm\Stat\Query\Field orFieldObjectId()
 * @method \Cms\Orm\Stat\Query orderAscObjectId()
 * @method \Cms\Orm\Stat\Query orderDescObjectId()
 * @method \Cms\Orm\Stat\Query groupByObjectId()
 * @method \Cms\Orm\Stat\Query\Field whereDateTime()
 * @method \Cms\Orm\Stat\Query\Field andFieldDateTime()
 * @method \Cms\Orm\Stat\Query\Field orFieldDateTime()
 * @method \Cms\Orm\Stat\Query orderAscDateTime()
 * @method \Cms\Orm\Stat\Query orderDescDateTime()
 * @method \Cms\Orm\Stat\Query groupByDateTime()
 * @method \Cms\Orm\Stat\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Stat\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Stat\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Stat\Record[] find()
 * @method \Cms\Orm\Stat\Record findFirst()
 * @method \Cms\Orm\Stat\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_stat';

	/**
	 * @return \Cms\Orm\Stat\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
