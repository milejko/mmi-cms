<?php

namespace Cms\Orm\Tag;

//<editor-fold defaultstate="collapsed" desc="cms_tag Query">
/**
 * @method \Cms\Orm\Tag\Query limit($limit = null)
 * @method \Cms\Orm\Tag\Query offset($offset = null)
 * @method \Cms\Orm\Tag\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Tag\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Tag\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Tag\Query resetOrder()
 * @method \Cms\Orm\Tag\Query resetWhere()
 * @method \Cms\Orm\Tag\QueryField whereId()
 * @method \Cms\Orm\Tag\QueryField andFieldId()
 * @method \Cms\Orm\Tag\QueryField orFieldId()
 * @method \Cms\Orm\Tag\Query orderAscId()
 * @method \Cms\Orm\Tag\Query orderDescId()
 * @method \Cms\Orm\Tag\Query groupById()
 * @method \Cms\Orm\Tag\QueryField whereTag()
 * @method \Cms\Orm\Tag\QueryField andFieldTag()
 * @method \Cms\Orm\Tag\QueryField orFieldTag()
 * @method \Cms\Orm\Tag\Query orderAscTag()
 * @method \Cms\Orm\Tag\Query orderDescTag()
 * @method \Cms\Orm\Tag\Query groupByTag()
 * @method \Cms\Orm\Tag\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Tag\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Tag\Record[] find()
 * @method \Cms\Orm\Tag\Record findFirst()
 * @method \Cms\Orm\Tag\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_tag';

	/**
	 * @return \Cms\Orm\Tag\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @param type $tagName
	 * @return \Cms\Orm\Tag\Query
	 */
	public static function byName($tagName) {
		return self::factory()
				->whereTag()->equals($tagName);
	}

}
