<?php

namespace Cms\Orm\Article;

//<editor-fold defaultstate="collapsed" desc="cms_article Query">
/**
 * @method \Cms\Orm\Article\Query limit($limit = null)
 * @method \Cms\Orm\Article\Query offset($offset = null)
 * @method \Cms\Orm\Article\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Article\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Article\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Article\Query resetOrder()
 * @method \Cms\Orm\Article\Query resetWhere()
 * @method \Cms\Orm\Article\Query\Field whereId()
 * @method \Cms\Orm\Article\Query\Field andFieldId()
 * @method \Cms\Orm\Article\Query\Field orFieldId()
 * @method \Cms\Orm\Article\Query orderAscId()
 * @method \Cms\Orm\Article\Query orderDescId()
 * @method \Cms\Orm\Article\Query groupById()
 * @method \Cms\Orm\Article\Query\Field whereLang()
 * @method \Cms\Orm\Article\Query\Field andFieldLang()
 * @method \Cms\Orm\Article\Query\Field orFieldLang()
 * @method \Cms\Orm\Article\Query orderAscLang()
 * @method \Cms\Orm\Article\Query orderDescLang()
 * @method \Cms\Orm\Article\Query groupByLang()
 * @method \Cms\Orm\Article\Query\Field whereTitle()
 * @method \Cms\Orm\Article\Query\Field andFieldTitle()
 * @method \Cms\Orm\Article\Query\Field orFieldTitle()
 * @method \Cms\Orm\Article\Query orderAscTitle()
 * @method \Cms\Orm\Article\Query orderDescTitle()
 * @method \Cms\Orm\Article\Query groupByTitle()
 * @method \Cms\Orm\Article\Query\Field whereUri()
 * @method \Cms\Orm\Article\Query\Field andFieldUri()
 * @method \Cms\Orm\Article\Query\Field orFieldUri()
 * @method \Cms\Orm\Article\Query orderAscUri()
 * @method \Cms\Orm\Article\Query orderDescUri()
 * @method \Cms\Orm\Article\Query groupByUri()
 * @method \Cms\Orm\Article\Query\Field whereDateAdd()
 * @method \Cms\Orm\Article\Query\Field andFieldDateAdd()
 * @method \Cms\Orm\Article\Query\Field orFieldDateAdd()
 * @method \Cms\Orm\Article\Query orderAscDateAdd()
 * @method \Cms\Orm\Article\Query orderDescDateAdd()
 * @method \Cms\Orm\Article\Query groupByDateAdd()
 * @method \Cms\Orm\Article\Query\Field whereDateModify()
 * @method \Cms\Orm\Article\Query\Field andFieldDateModify()
 * @method \Cms\Orm\Article\Query\Field orFieldDateModify()
 * @method \Cms\Orm\Article\Query orderAscDateModify()
 * @method \Cms\Orm\Article\Query orderDescDateModify()
 * @method \Cms\Orm\Article\Query groupByDateModify()
 * @method \Cms\Orm\Article\Query\Field whereText()
 * @method \Cms\Orm\Article\Query\Field andFieldText()
 * @method \Cms\Orm\Article\Query\Field orFieldText()
 * @method \Cms\Orm\Article\Query orderAscText()
 * @method \Cms\Orm\Article\Query orderDescText()
 * @method \Cms\Orm\Article\Query groupByText()
 * @method \Cms\Orm\Article\Query\Field whereNoindex()
 * @method \Cms\Orm\Article\Query\Field andFieldNoindex()
 * @method \Cms\Orm\Article\Query\Field orFieldNoindex()
 * @method \Cms\Orm\Article\Query orderAscNoindex()
 * @method \Cms\Orm\Article\Query orderDescNoindex()
 * @method \Cms\Orm\Article\Query groupByNoindex()
 * @method \Cms\Orm\Article\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Article\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Article\Record[] find()
 * @method \Cms\Orm\Article\Record findFirst()
 * @method \Cms\Orm\Article\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_article';

	/**
	 * @return \Cms\Orm\Article\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @param string $uri
	 * @return \Cms\Orm\Article\Query
	 */
	public static function byUri($uri) {
		return self::factory()
				->whereUri()->equals($uri);
	}

}
