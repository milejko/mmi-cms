<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsWidgetTextQuery">
/**
 * @method CmsWidgetTextQuery limit($limit = null)
 * @method CmsWidgetTextQuery offset($offset = null)
 * @method CmsWidgetTextQuery orderAsc($fieldName, $tableName = null)
 * @method CmsWidgetTextQuery orderDesc($fieldName, $tableName = null)
 * @method CmsWidgetTextQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsWidgetTextQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsWidgetTextQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsWidgetTextQuery resetOrder()
 * @method CmsWidgetTextQuery resetWhere()
 * @method QueryHelper\CmsWidgetTextQueryField whereId()
 * @method QueryHelper\CmsWidgetTextQueryField andFieldId()
 * @method QueryHelper\CmsWidgetTextQueryField orFieldId()
 * @method CmsWidgetTextQuery orderAscId()
 * @method CmsWidgetTextQuery orderDescId()
 * @method CmsWidgetTextQuery groupById()
 * @method QueryHelper\CmsWidgetTextQueryField whereData()
 * @method QueryHelper\CmsWidgetTextQueryField andFieldData()
 * @method QueryHelper\CmsWidgetTextQueryField orFieldData()
 * @method CmsWidgetTextQuery orderAscData()
 * @method CmsWidgetTextQuery orderDescData()
 * @method CmsWidgetTextQuery groupByData()
 * @method QueryHelper\CmsWidgetTextQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsWidgetTextQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsWidgetTextQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsWidgetTextQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsWidgetTextQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsWidgetTextRecord[] find()
 * @method CmsWidgetTextRecord findFirst()
 * @method CmsWidgetTextRecord findPk($value)
 */
//</editor-fold>
class CmsWidgetTextQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_widget_text';

	/**
	 * @return CmsWidgetTextQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
