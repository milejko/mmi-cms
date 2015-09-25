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
 * @method \Cms\Orm\Article\QueryField whereId()
 * @method \Cms\Orm\Article\QueryField andFieldId()
 * @method \Cms\Orm\Article\QueryField orFieldId()
 * @method \Cms\Orm\Article\Query orderAscId()
 * @method \Cms\Orm\Article\Query orderDescId()
 * @method \Cms\Orm\Article\Query groupById()
 * @method \Cms\Orm\Article\QueryField whereLang()
 * @method \Cms\Orm\Article\QueryField andFieldLang()
 * @method \Cms\Orm\Article\QueryField orFieldLang()
 * @method \Cms\Orm\Article\Query orderAscLang()
 * @method \Cms\Orm\Article\Query orderDescLang()
 * @method \Cms\Orm\Article\Query groupByLang()
 * @method \Cms\Orm\Article\QueryField whereTitle()
 * @method \Cms\Orm\Article\QueryField andFieldTitle()
 * @method \Cms\Orm\Article\QueryField orFieldTitle()
 * @method \Cms\Orm\Article\Query orderAscTitle()
 * @method \Cms\Orm\Article\Query orderDescTitle()
 * @method \Cms\Orm\Article\Query groupByTitle()
 * @method \Cms\Orm\Article\QueryField whereUri()
 * @method \Cms\Orm\Article\QueryField andFieldUri()
 * @method \Cms\Orm\Article\QueryField orFieldUri()
 * @method \Cms\Orm\Article\Query orderAscUri()
 * @method \Cms\Orm\Article\Query orderDescUri()
 * @method \Cms\Orm\Article\Query groupByUri()
 * @method \Cms\Orm\Article\QueryField whereDateAdd()
 * @method \Cms\Orm\Article\QueryField andFieldDateAdd()
 * @method \Cms\Orm\Article\QueryField orFieldDateAdd()
 * @method \Cms\Orm\Article\Query orderAscDateAdd()
 * @method \Cms\Orm\Article\Query orderDescDateAdd()
 * @method \Cms\Orm\Article\Query groupByDateAdd()
 * @method \Cms\Orm\Article\QueryField whereDateModify()
 * @method \Cms\Orm\Article\QueryField andFieldDateModify()
 * @method \Cms\Orm\Article\QueryField orFieldDateModify()
 * @method \Cms\Orm\Article\Query orderAscDateModify()
 * @method \Cms\Orm\Article\Query orderDescDateModify()
 * @method \Cms\Orm\Article\Query groupByDateModify()
 * @method \Cms\Orm\Article\QueryField whereText()
 * @method \Cms\Orm\Article\QueryField andFieldText()
 * @method \Cms\Orm\Article\QueryField orFieldText()
 * @method \Cms\Orm\Article\Query orderAscText()
 * @method \Cms\Orm\Article\Query orderDescText()
 * @method \Cms\Orm\Article\Query groupByText()
 * @method \Cms\Orm\Article\QueryField whereNoindex()
 * @method \Cms\Orm\Article\QueryField andFieldNoindex()
 * @method \Cms\Orm\Article\QueryField orFieldNoindex()
 * @method \Cms\Orm\Article\Query orderAscNoindex()
 * @method \Cms\Orm\Article\Query orderDescNoindex()
 * @method \Cms\Orm\Article\Query groupByNoindex()
 * @method \Cms\Orm\Article\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Article\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Article\QueryJoin joinLeft($tableName, $targetTableName = null)
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
