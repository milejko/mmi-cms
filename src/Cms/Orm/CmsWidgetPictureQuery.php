<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsWidgetPictureQuery">
/**
 * @method CmsWidgetPictureQuery limit($limit = null)
 * @method CmsWidgetPictureQuery offset($offset = null)
 * @method CmsWidgetPictureQuery orderAsc($fieldName, $tableName = null)
 * @method CmsWidgetPictureQuery orderDesc($fieldName, $tableName = null)
 * @method CmsWidgetPictureQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsWidgetPictureQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsWidgetPictureQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsWidgetPictureQuery resetOrder()
 * @method CmsWidgetPictureQuery resetWhere()
 * @method QueryHelper\CmsWidgetPictureQueryField whereId()
 * @method QueryHelper\CmsWidgetPictureQueryField andFieldId()
 * @method QueryHelper\CmsWidgetPictureQueryField orFieldId()
 * @method CmsWidgetPictureQuery orderAscId()
 * @method CmsWidgetPictureQuery orderDescId()
 * @method CmsWidgetPictureQuery groupById()
 * @method QueryHelper\CmsWidgetPictureQueryField whereDateAdd()
 * @method QueryHelper\CmsWidgetPictureQueryField andFieldDateAdd()
 * @method QueryHelper\CmsWidgetPictureQueryField orFieldDateAdd()
 * @method CmsWidgetPictureQuery orderAscDateAdd()
 * @method CmsWidgetPictureQuery orderDescDateAdd()
 * @method CmsWidgetPictureQuery groupByDateAdd()
 * @method QueryHelper\CmsWidgetPictureQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsWidgetPictureQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsWidgetPictureQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsWidgetPictureQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsWidgetPictureQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsWidgetPictureRecord[] find()
 * @method CmsWidgetPictureRecord findFirst()
 * @method CmsWidgetPictureRecord findPk($value)
 */
//</editor-fold>
class CmsWidgetPictureQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_widget_picture';

	/**
	 * @return CmsWidgetPictureQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
