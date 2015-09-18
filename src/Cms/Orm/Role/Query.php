<?php

namespace Cms\Orm\Role;

//<editor-fold defaultstate="collapsed" desc="cms_role Query">
/**
 * @method \Cms\Orm\Role\Query limit($limit = null)
 * @method \Cms\Orm\Role\Query offset($offset = null)
 * @method \Cms\Orm\Role\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Role\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Role\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Role\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Role\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Role\Query resetOrder()
 * @method \Cms\Orm\Role\Query resetWhere()
 * @method \Cms\Orm\Role\Query\Field whereId()
 * @method \Cms\Orm\Role\Query\Field andFieldId()
 * @method \Cms\Orm\Role\Query\Field orFieldId()
 * @method \Cms\Orm\Role\Query orderAscId()
 * @method \Cms\Orm\Role\Query orderDescId()
 * @method \Cms\Orm\Role\Query groupById()
 * @method \Cms\Orm\Role\Query\Field whereName()
 * @method \Cms\Orm\Role\Query\Field andFieldName()
 * @method \Cms\Orm\Role\Query\Field orFieldName()
 * @method \Cms\Orm\Role\Query orderAscName()
 * @method \Cms\Orm\Role\Query orderDescName()
 * @method \Cms\Orm\Role\Query groupByName()
 * @method \Cms\Orm\Role\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Role\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Role\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Role\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Role\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Role\Record[] find()
 * @method \Cms\Orm\Role\Record findFirst()
 * @method \Cms\Orm\Role\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_role';

	/**
	 * @return \Cms\Orm\Role\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
