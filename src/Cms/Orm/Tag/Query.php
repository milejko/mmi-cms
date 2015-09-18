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
 * @method \Cms\Orm\Tag\Query\Field whereId()
 * @method \Cms\Orm\Tag\Query\Field andFieldId()
 * @method \Cms\Orm\Tag\Query\Field orFieldId()
 * @method \Cms\Orm\Tag\Query orderAscId()
 * @method \Cms\Orm\Tag\Query orderDescId()
 * @method \Cms\Orm\Tag\Query groupById()
 * @method \Cms\Orm\Tag\Query\Field whereTag()
 * @method \Cms\Orm\Tag\Query\Field andFieldTag()
 * @method \Cms\Orm\Tag\Query\Field orFieldTag()
 * @method \Cms\Orm\Tag\Query orderAscTag()
 * @method \Cms\Orm\Tag\Query orderDescTag()
 * @method \Cms\Orm\Tag\Query groupByTag()
 * @method \Cms\Orm\Tag\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Tag\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Tag\Query\Join joinLeft($tableName, $targetTableName = null)
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
