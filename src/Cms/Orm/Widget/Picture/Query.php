<?php

namespace Cms\Orm\Widget\Picture;

//<editor-fold defaultstate="collapsed" desc="cms_widget_picture Query">
/**
 * @method \Cms\Orm\Widget\Picture\Query limit($limit = null)
 * @method \Cms\Orm\Widget\Picture\Query offset($offset = null)
 * @method \Cms\Orm\Widget\Picture\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Picture\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Picture\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Widget\Picture\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Widget\Picture\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Widget\Picture\Query resetOrder()
 * @method \Cms\Orm\Widget\Picture\Query resetWhere()
 * @method \Cms\Orm\Widget\Picture\QueryField whereId()
 * @method \Cms\Orm\Widget\Picture\QueryField andFieldId()
 * @method \Cms\Orm\Widget\Picture\QueryField orFieldId()
 * @method \Cms\Orm\Widget\Picture\Query orderAscId()
 * @method \Cms\Orm\Widget\Picture\Query orderDescId()
 * @method \Cms\Orm\Widget\Picture\Query groupById()
 * @method \Cms\Orm\Widget\Picture\QueryField whereDateAdd()
 * @method \Cms\Orm\Widget\Picture\QueryField andFieldDateAdd()
 * @method \Cms\Orm\Widget\Picture\QueryField orFieldDateAdd()
 * @method \Cms\Orm\Widget\Picture\Query orderAscDateAdd()
 * @method \Cms\Orm\Widget\Picture\Query orderDescDateAdd()
 * @method \Cms\Orm\Widget\Picture\Query groupByDateAdd()
 * @method \Cms\Orm\Widget\Picture\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Picture\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Picture\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Picture\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Widget\Picture\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Widget\Picture\Record[] find()
 * @method \Cms\Orm\Widget\Picture\Record findFirst()
 * @method \Cms\Orm\Widget\Picture\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_widget_picture';

	/**
	 * @return \Cms\Orm\Widget\Picture\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
