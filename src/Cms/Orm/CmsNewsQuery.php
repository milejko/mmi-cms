<?php

namespace Cms\Orm;

//<editor-fold defaultstate="collapsed" desc="CmsNewsQuery">
/**
 * @method CmsNewsQuery limit($limit = null)
 * @method CmsNewsQuery offset($offset = null)
 * @method CmsNewsQuery orderAsc($fieldName, $tableName = null)
 * @method CmsNewsQuery orderDesc($fieldName, $tableName = null)
 * @method CmsNewsQuery andQuery(\Mmi\Orm\Query $query)
 * @method CmsNewsQuery whereQuery(\Mmi\Orm\Query $query)
 * @method CmsNewsQuery orQuery(\Mmi\Orm\Query $query)
 * @method CmsNewsQuery resetOrder()
 * @method CmsNewsQuery resetWhere()
 * @method QueryHelper\CmsNewsQueryField whereId()
 * @method QueryHelper\CmsNewsQueryField andFieldId()
 * @method QueryHelper\CmsNewsQueryField orFieldId()
 * @method CmsNewsQuery orderAscId()
 * @method CmsNewsQuery orderDescId()
 * @method CmsNewsQuery groupById()
 * @method QueryHelper\CmsNewsQueryField whereLang()
 * @method QueryHelper\CmsNewsQueryField andFieldLang()
 * @method QueryHelper\CmsNewsQueryField orFieldLang()
 * @method CmsNewsQuery orderAscLang()
 * @method CmsNewsQuery orderDescLang()
 * @method CmsNewsQuery groupByLang()
 * @method QueryHelper\CmsNewsQueryField whereTitle()
 * @method QueryHelper\CmsNewsQueryField andFieldTitle()
 * @method QueryHelper\CmsNewsQueryField orFieldTitle()
 * @method CmsNewsQuery orderAscTitle()
 * @method CmsNewsQuery orderDescTitle()
 * @method CmsNewsQuery groupByTitle()
 * @method QueryHelper\CmsNewsQueryField whereLead()
 * @method QueryHelper\CmsNewsQueryField andFieldLead()
 * @method QueryHelper\CmsNewsQueryField orFieldLead()
 * @method CmsNewsQuery orderAscLead()
 * @method CmsNewsQuery orderDescLead()
 * @method CmsNewsQuery groupByLead()
 * @method QueryHelper\CmsNewsQueryField whereText()
 * @method QueryHelper\CmsNewsQueryField andFieldText()
 * @method QueryHelper\CmsNewsQueryField orFieldText()
 * @method CmsNewsQuery orderAscText()
 * @method CmsNewsQuery orderDescText()
 * @method CmsNewsQuery groupByText()
 * @method QueryHelper\CmsNewsQueryField whereDateAdd()
 * @method QueryHelper\CmsNewsQueryField andFieldDateAdd()
 * @method QueryHelper\CmsNewsQueryField orFieldDateAdd()
 * @method CmsNewsQuery orderAscDateAdd()
 * @method CmsNewsQuery orderDescDateAdd()
 * @method CmsNewsQuery groupByDateAdd()
 * @method QueryHelper\CmsNewsQueryField whereDateModify()
 * @method QueryHelper\CmsNewsQueryField andFieldDateModify()
 * @method QueryHelper\CmsNewsQueryField orFieldDateModify()
 * @method CmsNewsQuery orderAscDateModify()
 * @method CmsNewsQuery orderDescDateModify()
 * @method CmsNewsQuery groupByDateModify()
 * @method QueryHelper\CmsNewsQueryField whereUri()
 * @method QueryHelper\CmsNewsQueryField andFieldUri()
 * @method QueryHelper\CmsNewsQueryField orFieldUri()
 * @method CmsNewsQuery orderAscUri()
 * @method CmsNewsQuery orderDescUri()
 * @method CmsNewsQuery groupByUri()
 * @method QueryHelper\CmsNewsQueryField whereInternal()
 * @method QueryHelper\CmsNewsQueryField andFieldInternal()
 * @method QueryHelper\CmsNewsQueryField orFieldInternal()
 * @method CmsNewsQuery orderAscInternal()
 * @method CmsNewsQuery orderDescInternal()
 * @method CmsNewsQuery groupByInternal()
 * @method QueryHelper\CmsNewsQueryField whereVisible()
 * @method QueryHelper\CmsNewsQueryField andFieldVisible()
 * @method QueryHelper\CmsNewsQueryField orFieldVisible()
 * @method CmsNewsQuery orderAscVisible()
 * @method CmsNewsQuery orderDescVisible()
 * @method CmsNewsQuery groupByVisible()
 * @method QueryHelper\CmsNewsQueryField andField($fieldName, $tableName = null)
 * @method QueryHelper\CmsNewsQueryField where($fieldName, $tableName = null)
 * @method QueryHelper\CmsNewsQueryField orField($fieldName, $tableName = null)
 * @method QueryHelper\CmsNewsQueryJoin join($tableName, $targetTableName = null)
 * @method QueryHelper\CmsNewsQueryJoin joinLeft($tableName, $targetTableName = null)
 * @method CmsNewsRecord[] find()
 * @method CmsNewsRecord findFirst()
 * @method CmsNewsRecord findPk($value)
 */
//</editor-fold>
class CmsNewsQuery extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_news';

	/**
	 * @return CmsNewsQuery
	 */
	public static function factory($tableName = null) {
		return new self($tableName);
	}

	/**
	 * Zapytanie językowe
	 * @return CmsNewsQuery
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
	 * @return CmsNewsQuery
	 */
	public static function active() {
		return self::lang()
				->whereVisible()->equals(1)
				->orderAscDateAdd();
	}

	/**
	 * Zapytanie o aktywne po uri
	 * @param string $uri
	 * @return CmsNewsQuery
	 */
	public static function activeByUri($uri) {
		return self::active()
				->whereUri()->equals($uri);
	}

	/**
	 * Zapytanie po uri
	 * @param string $uri
	 * @return CmsNewsQuery
	 */
	public static function byUri($uri) {
		return self::lang()
				->whereUri()->equals($uri);
	}

}
