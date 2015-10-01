<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsStatLabelQuery">
/**
 * @method CmsStatLabelQuery limit($limit = null)
 * @method CmsStatLabelQuery offset($offset = null)
 * @method CmsStatLabelQuery orderAsc($fieldName, $tableName = null)
 * @method CmsStatLabelQuery orderDesc($fieldName, $tableName = null)
 * @method CmsStatLabelQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsStatLabelQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsStatLabelQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsStatLabelQuery resetOrder()
 * @method CmsStatLabelQuery resetWhere()
 * @method QueryHelper\CmsStatLabelQueryField whereId()
 * @method QueryHelper\CmsStatLabelQueryField andFieldId()
 * @method QueryHelper\CmsStatLabelQueryField orFieldId()
 * @method CmsStatLabelQuery orderAscId()
 * @method CmsStatLabelQuery orderDescId()
 * @method CmsStatLabelQuery groupById()
 * @method QueryHelper\CmsStatLabelQueryField whereLang()
 * @method QueryHelper\CmsStatLabelQueryField andFieldLang()
 * @method QueryHelper\CmsStatLabelQueryField orFieldLang()
 * @method CmsStatLabelQuery orderAscLang()
 * @method CmsStatLabelQuery orderDescLang()
 * @method CmsStatLabelQuery groupByLang()
 * @method QueryHelper\CmsStatLabelQueryField whereObject()
 * @method QueryHelper\CmsStatLabelQueryField andFieldObject()
 * @method QueryHelper\CmsStatLabelQueryField orFieldObject()
 * @method CmsStatLabelQuery orderAscObject()
 * @method CmsStatLabelQuery orderDescObject()
 * @method CmsStatLabelQuery groupByObject()
 * @method QueryHelper\CmsStatLabelQueryField whereLabel()
 * @method QueryHelper\CmsStatLabelQueryField andFieldLabel()
 * @method QueryHelper\CmsStatLabelQueryField orFieldLabel()
 * @method CmsStatLabelQuery orderAscLabel()
 * @method CmsStatLabelQuery orderDescLabel()
 * @method CmsStatLabelQuery groupByLabel()
 * @method QueryHelper\CmsStatLabelQueryField whereDescription()
 * @method QueryHelper\CmsStatLabelQueryField andFieldDescription()
 * @method QueryHelper\CmsStatLabelQueryField orFieldDescription()
 * @method CmsStatLabelQuery orderAscDescription()
 * @method CmsStatLabelQuery orderDescDescription()
 * @method CmsStatLabelQuery groupByDescription()
 * @method QueryHelper\CmsStatLabelQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsStatLabelQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsStatLabelQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsStatLabelQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsStatLabelQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsStatLabelRecord[] find()
 * @method CmsStatLabelRecord findFirst()
 * @method CmsStatLabelRecord findPk($value)
 */
//</editor-fold>
class CmsStatLabelQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_stat_label';

	/**
	 * @return CmsStatLabelQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

}
