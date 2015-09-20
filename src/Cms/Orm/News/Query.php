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
 * @method \Cms\Orm\News\Query\Field whereId()
 * @method \Cms\Orm\News\Query\Field andFieldId()
 * @method \Cms\Orm\News\Query\Field orFieldId()
 * @method \Cms\Orm\News\Query orderAscId()
 * @method \Cms\Orm\News\Query orderDescId()
 * @method \Cms\Orm\News\Query groupById()
 * @method \Cms\Orm\News\Query\Field whereLang()
 * @method \Cms\Orm\News\Query\Field andFieldLang()
 * @method \Cms\Orm\News\Query\Field orFieldLang()
 * @method \Cms\Orm\News\Query orderAscLang()
 * @method \Cms\Orm\News\Query orderDescLang()
 * @method \Cms\Orm\News\Query groupByLang()
 * @method \Cms\Orm\News\Query\Field whereTitle()
 * @method \Cms\Orm\News\Query\Field andFieldTitle()
 * @method \Cms\Orm\News\Query\Field orFieldTitle()
 * @method \Cms\Orm\News\Query orderAscTitle()
 * @method \Cms\Orm\News\Query orderDescTitle()
 * @method \Cms\Orm\News\Query groupByTitle()
 * @method \Cms\Orm\News\Query\Field whereLead()
 * @method \Cms\Orm\News\Query\Field andFieldLead()
 * @method \Cms\Orm\News\Query\Field orFieldLead()
 * @method \Cms\Orm\News\Query orderAscLead()
 * @method \Cms\Orm\News\Query orderDescLead()
 * @method \Cms\Orm\News\Query groupByLead()
 * @method \Cms\Orm\News\Query\Field whereText()
 * @method \Cms\Orm\News\Query\Field andFieldText()
 * @method \Cms\Orm\News\Query\Field orFieldText()
 * @method \Cms\Orm\News\Query orderAscText()
 * @method \Cms\Orm\News\Query orderDescText()
 * @method \Cms\Orm\News\Query groupByText()
 * @method \Cms\Orm\News\Query\Field whereDateAdd()
 * @method \Cms\Orm\News\Query\Field andFieldDateAdd()
 * @method \Cms\Orm\News\Query\Field orFieldDateAdd()
 * @method \Cms\Orm\News\Query orderAscDateAdd()
 * @method \Cms\Orm\News\Query orderDescDateAdd()
 * @method \Cms\Orm\News\Query groupByDateAdd()
 * @method \Cms\Orm\News\Query\Field whereDateModify()
 * @method \Cms\Orm\News\Query\Field andFieldDateModify()
 * @method \Cms\Orm\News\Query\Field orFieldDateModify()
 * @method \Cms\Orm\News\Query orderAscDateModify()
 * @method \Cms\Orm\News\Query orderDescDateModify()
 * @method \Cms\Orm\News\Query groupByDateModify()
 * @method \Cms\Orm\News\Query\Field whereUri()
 * @method \Cms\Orm\News\Query\Field andFieldUri()
 * @method \Cms\Orm\News\Query\Field orFieldUri()
 * @method \Cms\Orm\News\Query orderAscUri()
 * @method \Cms\Orm\News\Query orderDescUri()
 * @method \Cms\Orm\News\Query groupByUri()
 * @method \Cms\Orm\News\Query\Field whereInternal()
 * @method \Cms\Orm\News\Query\Field andFieldInternal()
 * @method \Cms\Orm\News\Query\Field orFieldInternal()
 * @method \Cms\Orm\News\Query orderAscInternal()
 * @method \Cms\Orm\News\Query orderDescInternal()
 * @method \Cms\Orm\News\Query groupByInternal()
 * @method \Cms\Orm\News\Query\Field whereVisible()
 * @method \Cms\Orm\News\Query\Field andFieldVisible()
 * @method \Cms\Orm\News\Query\Field orFieldVisible()
 * @method \Cms\Orm\News\Query orderAscVisible()
 * @method \Cms\Orm\News\Query orderDescVisible()
 * @method \Cms\Orm\News\Query groupByVisible()
 * @method \Cms\Orm\News\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\News\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\News\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\News\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\News\Query\Join joinLeft($tableName, $targetTableName = null)
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
