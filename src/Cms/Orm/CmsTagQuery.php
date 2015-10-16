<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsTagQuery">
/**
 * @method CmsTagQuery limit($limit = null)
 * @method CmsTagQuery offset($offset = null)
 * @method CmsTagQuery orderAsc($fieldName, $tableName = null)
 * @method CmsTagQuery orderDesc($fieldName, $tableName = null)
 * @method CmsTagQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsTagQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsTagQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsTagQuery resetOrder()
 * @method CmsTagQuery resetWhere()
 * @method QueryHelper\CmsTagQueryField whereId()
 * @method QueryHelper\CmsTagQueryField andFieldId()
 * @method QueryHelper\CmsTagQueryField orFieldId()
 * @method CmsTagQuery orderAscId()
 * @method CmsTagQuery orderDescId()
 * @method CmsTagQuery groupById()
 * @method QueryHelper\CmsTagQueryField whereTag()
 * @method QueryHelper\CmsTagQueryField andFieldTag()
 * @method QueryHelper\CmsTagQueryField orFieldTag()
 * @method CmsTagQuery orderAscTag()
 * @method CmsTagQuery orderDescTag()
 * @method CmsTagQuery groupByTag()
 * @method QueryHelper\CmsTagQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsTagQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsTagQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsTagRecord[] find()
 * @method CmsTagRecord findFirst()
 * @method CmsTagRecord findPk($value)
 */
//</editor-fold>
class CmsTagQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_tag';

	/**
	 * Po nazwie
	 * @param string $tagName
	 * @return CmsTagQuery
	 */
	public static function byName($tagName) {
		return (new self)
				->whereTag()->equals($tagName);
	}

}
