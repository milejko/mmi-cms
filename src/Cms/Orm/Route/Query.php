<?php

namespace Cms\Orm\Route;

//<editor-fold defaultstate="collapsed" desc="cms_route Query">
/**
 * @method \Cms\Orm\Route\Query limit($limit = null)
 * @method \Cms\Orm\Route\Query offset($offset = null)
 * @method \Cms\Orm\Route\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Route\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Route\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Route\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Route\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Route\Query resetOrder()
 * @method \Cms\Orm\Route\Query resetWhere()
 * @method \Cms\Orm\Route\QueryField whereId()
 * @method \Cms\Orm\Route\QueryField andFieldId()
 * @method \Cms\Orm\Route\QueryField orFieldId()
 * @method \Cms\Orm\Route\Query orderAscId()
 * @method \Cms\Orm\Route\Query orderDescId()
 * @method \Cms\Orm\Route\Query groupById()
 * @method \Cms\Orm\Route\QueryField wherePattern()
 * @method \Cms\Orm\Route\QueryField andFieldPattern()
 * @method \Cms\Orm\Route\QueryField orFieldPattern()
 * @method \Cms\Orm\Route\Query orderAscPattern()
 * @method \Cms\Orm\Route\Query orderDescPattern()
 * @method \Cms\Orm\Route\Query groupByPattern()
 * @method \Cms\Orm\Route\QueryField whereReplace()
 * @method \Cms\Orm\Route\QueryField andFieldReplace()
 * @method \Cms\Orm\Route\QueryField orFieldReplace()
 * @method \Cms\Orm\Route\Query orderAscReplace()
 * @method \Cms\Orm\Route\Query orderDescReplace()
 * @method \Cms\Orm\Route\Query groupByReplace()
 * @method \Cms\Orm\Route\QueryField whereDefault()
 * @method \Cms\Orm\Route\QueryField andFieldDefault()
 * @method \Cms\Orm\Route\QueryField orFieldDefault()
 * @method \Cms\Orm\Route\Query orderAscDefault()
 * @method \Cms\Orm\Route\Query orderDescDefault()
 * @method \Cms\Orm\Route\Query groupByDefault()
 * @method \Cms\Orm\Route\QueryField whereOrder()
 * @method \Cms\Orm\Route\QueryField andFieldOrder()
 * @method \Cms\Orm\Route\QueryField orFieldOrder()
 * @method \Cms\Orm\Route\Query orderAscOrder()
 * @method \Cms\Orm\Route\Query orderDescOrder()
 * @method \Cms\Orm\Route\Query groupByOrder()
 * @method \Cms\Orm\Route\QueryField whereActive()
 * @method \Cms\Orm\Route\QueryField andFieldActive()
 * @method \Cms\Orm\Route\QueryField orFieldActive()
 * @method \Cms\Orm\Route\Query orderAscActive()
 * @method \Cms\Orm\Route\Query orderDescActive()
 * @method \Cms\Orm\Route\Query groupByActive()
 * @method \Cms\Orm\Route\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Route\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Route\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Route\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Route\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Route\Record[] find()
 * @method \Cms\Orm\Route\Record findFirst()
 * @method \Cms\Orm\Route\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_route';
	
	/**
	 * @return \Cms\Orm\Route\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @return \Cms\Orm\Route\Query
	 */
	public static function active() {
		return self::factory()
				->whereActive()->equals(1)
				->orderAscOrder();
	}

}
