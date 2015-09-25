<?php

namespace Cms\Orm\News;

//<editor-fold defaultstate="collapsed" desc="cms_news Query">
/**
 * @method \Cms\Orm\News\Query limit($limit = null)
 * @method \Cms\Orm\News\Query offset($offset = null)
 * @method \Cms\Orm\News\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\News\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\News\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\News\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\News\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\News\Query resetOrder()
 * @method \Cms\Orm\News\Query resetWhere()
 * @method \Cms\Orm\News\QueryField whereId()
 * @method \Cms\Orm\News\QueryField andFieldId()
 * @method \Cms\Orm\News\QueryField orFieldId()
 * @method \Cms\Orm\News\Query orderAscId()
 * @method \Cms\Orm\News\Query orderDescId()
 * @method \Cms\Orm\News\Query groupById()
 * @method \Cms\Orm\News\QueryField whereLang()
 * @method \Cms\Orm\News\QueryField andFieldLang()
 * @method \Cms\Orm\News\QueryField orFieldLang()
 * @method \Cms\Orm\News\Query orderAscLang()
 * @method \Cms\Orm\News\Query orderDescLang()
 * @method \Cms\Orm\News\Query groupByLang()
 * @method \Cms\Orm\News\QueryField whereTitle()
 * @method \Cms\Orm\News\QueryField andFieldTitle()
 * @method \Cms\Orm\News\QueryField orFieldTitle()
 * @method \Cms\Orm\News\Query orderAscTitle()
 * @method \Cms\Orm\News\Query orderDescTitle()
 * @method \Cms\Orm\News\Query groupByTitle()
 * @method \Cms\Orm\News\QueryField whereLead()
 * @method \Cms\Orm\News\QueryField andFieldLead()
 * @method \Cms\Orm\News\QueryField orFieldLead()
 * @method \Cms\Orm\News\Query orderAscLead()
 * @method \Cms\Orm\News\Query orderDescLead()
 * @method \Cms\Orm\News\Query groupByLead()
 * @method \Cms\Orm\News\QueryField whereText()
 * @method \Cms\Orm\News\QueryField andFieldText()
 * @method \Cms\Orm\News\QueryField orFieldText()
 * @method \Cms\Orm\News\Query orderAscText()
 * @method \Cms\Orm\News\Query orderDescText()
 * @method \Cms\Orm\News\Query groupByText()
 * @method \Cms\Orm\News\QueryField whereDateAdd()
 * @method \Cms\Orm\News\QueryField andFieldDateAdd()
 * @method \Cms\Orm\News\QueryField orFieldDateAdd()
 * @method \Cms\Orm\News\Query orderAscDateAdd()
 * @method \Cms\Orm\News\Query orderDescDateAdd()
 * @method \Cms\Orm\News\Query groupByDateAdd()
 * @method \Cms\Orm\News\QueryField whereDateModify()
 * @method \Cms\Orm\News\QueryField andFieldDateModify()
 * @method \Cms\Orm\News\QueryField orFieldDateModify()
 * @method \Cms\Orm\News\Query orderAscDateModify()
 * @method \Cms\Orm\News\Query orderDescDateModify()
 * @method \Cms\Orm\News\Query groupByDateModify()
 * @method \Cms\Orm\News\QueryField whereUri()
 * @method \Cms\Orm\News\QueryField andFieldUri()
 * @method \Cms\Orm\News\QueryField orFieldUri()
 * @method \Cms\Orm\News\Query orderAscUri()
 * @method \Cms\Orm\News\Query orderDescUri()
 * @method \Cms\Orm\News\Query groupByUri()
 * @method \Cms\Orm\News\QueryField whereInternal()
 * @method \Cms\Orm\News\QueryField andFieldInternal()
 * @method \Cms\Orm\News\QueryField orFieldInternal()
 * @method \Cms\Orm\News\Query orderAscInternal()
 * @method \Cms\Orm\News\Query orderDescInternal()
 * @method \Cms\Orm\News\Query groupByInternal()
 * @method \Cms\Orm\News\QueryField whereVisible()
 * @method \Cms\Orm\News\QueryField andFieldVisible()
 * @method \Cms\Orm\News\QueryField orFieldVisible()
 * @method \Cms\Orm\News\Query orderAscVisible()
 * @method \Cms\Orm\News\Query orderDescVisible()
 * @method \Cms\Orm\News\Query groupByVisible()
 * @method \Cms\Orm\News\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\News\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\News\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\News\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\News\QueryJoin joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\News\Record[] find()
 * @method \Cms\Orm\News\Record findFirst()
 * @method \Cms\Orm\News\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_news';

	/**
	 * @return \Cms\Orm\News\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * Zapytanie językowe
	 * @return \Cms\Orm\News\Query
	 */
	public static function lang() {
		if (!\Mmi\App\FrontController::getInstance()->getRequest()->lang) {
			return self::factory();
		}
		return self::factory()
				->whereLang()->equals(\Mmi\App\FrontController::getInstance()->getRequest()->lang)
				->orFieldLang()->equals(null)
				->orderDescLang();
	}

	/**
	 * Zapytanie o aktywne
	 * @return \Cms\Orm\News\Query
	 */
	public static function active() {
		return self::lang()
				->whereVisible()->equals(1)
				->orderAscDateAdd();
	}

	/**
	 * Zapytanie o aktywne po uri
	 * @param string $uri
	 * @return \Cms\Orm\News\Query
	 */
	public static function activeByUri($uri) {
		return self::active()
				->whereUri()->equals($uri);
	}

	/**
	 * Zapytanie po uri
	 * @param string $uri
	 * @return \Cms\Orm\News\Query
	 */
	public static function byUri($uri) {
		return self::lang()
				->whereUri()->equals($uri);
	}

}
