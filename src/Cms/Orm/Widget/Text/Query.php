<?php

namespace Cms\Orm\Widget\Text;

//<editor-fold defaultstate="collapsed" desc="cms_widget_text Query">
/**
 * @method \Cms\Orm\Widget\Text\Query limit($limit = null)
 * @method \Cms\Orm\Widget\Text\Query offset($offset = null)
 * @method \Cms\Orm\Widget\Text\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Text\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Text\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Widget\Text\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Widget\Text\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Widget\Text\Query resetOrder()
 * @method \Cms\Orm\Widget\Text\Query resetWhere()
 * @method \Cms\Orm\Widget\Text\QueryField whereId()
 * @method \Cms\Orm\Widget\Text\QueryField andFieldId()
 * @method \Cms\Orm\Widget\Text\QueryField orFieldId()
 * @method \Cms\Orm\Widget\Text\Query orderAscId()
 * @method \Cms\Orm\Widget\Text\Query orderDescId()
 * @method \Cms\Orm\Widget\Text\Query groupById()
 * @method \Cms\Orm\Widget\Text\QueryField whereData()
 * @method \Cms\Orm\Widget\Text\QueryField andFieldData()
 * @method \Cms\Orm\Widget\Text\QueryField orFieldData()
 * @method \Cms\Orm\Widget\Text\Query orderAscData()
 * @method \Cms\Orm\Widget\Text\Query orderDescData()
 * @method \Cms\Orm\Widget\Text\Query groupByData()
 * @method \Cms\Orm\Widget\Text\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Text\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Text\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Widget\Text\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Widget\Text\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Widget\Text\Record[] find()
 * @method \Cms\Orm\Widget\Text\Record findFirst()
 * @method \Cms\Orm\Widget\Text\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_widget_text';

	/**
	 * @return \Cms\Orm\Widget\Text\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

}
