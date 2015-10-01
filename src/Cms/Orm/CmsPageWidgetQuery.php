<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsPageWidgetQuery">
/**
 * @method CmsPageWidgetQuery limit($limit = null)
 * @method CmsPageWidgetQuery offset($offset = null)
 * @method CmsPageWidgetQuery orderAsc($fieldName, $tableName = null)
 * @method CmsPageWidgetQuery orderDesc($fieldName, $tableName = null)
 * @method CmsPageWidgetQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsPageWidgetQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsPageWidgetQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsPageWidgetQuery resetOrder()
 * @method CmsPageWidgetQuery resetWhere()
 * @method QueryHelper\CmsPageWidgetQueryField whereId()
 * @method QueryHelper\CmsPageWidgetQueryField andFieldId()
 * @method QueryHelper\CmsPageWidgetQueryField orFieldId()
 * @method CmsPageWidgetQuery orderAscId()
 * @method CmsPageWidgetQuery orderDescId()
 * @method CmsPageWidgetQuery groupById()
 * @method QueryHelper\CmsPageWidgetQueryField whereName()
 * @method QueryHelper\CmsPageWidgetQueryField andFieldName()
 * @method QueryHelper\CmsPageWidgetQueryField orFieldName()
 * @method CmsPageWidgetQuery orderAscName()
 * @method CmsPageWidgetQuery orderDescName()
 * @method CmsPageWidgetQuery groupByName()
 * @method QueryHelper\CmsPageWidgetQueryField whereModule()
 * @method QueryHelper\CmsPageWidgetQueryField andFieldModule()
 * @method QueryHelper\CmsPageWidgetQueryField orFieldModule()
 * @method CmsPageWidgetQuery orderAscModule()
 * @method CmsPageWidgetQuery orderDescModule()
 * @method CmsPageWidgetQuery groupByModule()
 * @method QueryHelper\CmsPageWidgetQueryField whereController()
 * @method QueryHelper\CmsPageWidgetQueryField andFieldController()
 * @method QueryHelper\CmsPageWidgetQueryField orFieldController()
 * @method CmsPageWidgetQuery orderAscController()
 * @method CmsPageWidgetQuery orderDescController()
 * @method CmsPageWidgetQuery groupByController()
 * @method QueryHelper\CmsPageWidgetQueryField whereAction()
 * @method QueryHelper\CmsPageWidgetQueryField andFieldAction()
 * @method QueryHelper\CmsPageWidgetQueryField orFieldAction()
 * @method CmsPageWidgetQuery orderAscAction()
 * @method CmsPageWidgetQuery orderDescAction()
 * @method CmsPageWidgetQuery groupByAction()
 * @method QueryHelper\CmsPageWidgetQueryField whereParams()
 * @method QueryHelper\CmsPageWidgetQueryField andFieldParams()
 * @method QueryHelper\CmsPageWidgetQueryField orFieldParams()
 * @method CmsPageWidgetQuery orderAscParams()
 * @method CmsPageWidgetQuery orderDescParams()
 * @method CmsPageWidgetQuery groupByParams()
 * @method QueryHelper\CmsPageWidgetQueryField whereActive()
 * @method QueryHelper\CmsPageWidgetQueryField andFieldActive()
 * @method QueryHelper\CmsPageWidgetQueryField orFieldActive()
 * @method CmsPageWidgetQuery orderAscActive()
 * @method CmsPageWidgetQuery orderDescActive()
 * @method CmsPageWidgetQuery groupByActive()
 * @method QueryHelper\CmsPageWidgetQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsPageWidgetQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsPageWidgetQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsPageWidgetQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsPageWidgetQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsPageWidgetRecord[] find()
 * @method CmsPageWidgetRecord findFirst()
 * @method CmsPageWidgetRecord findPk($value)
 */
//</editor-fold>
class CmsPageWidgetQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_page_widget';

	/**
	 * @return CmsPageWidgetQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
