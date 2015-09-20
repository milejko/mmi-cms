<?php

namespace Cms\Orm\Navigation;

//<editor-fold defaultstate="collapsed" desc="cms_navigation Query">
/**
 * @method \Cms\Orm\Navigation\Query limit($limit = null)
 * @method \Cms\Orm\Navigation\Query offset($offset = null)
 * @method \Cms\Orm\Navigation\Query orderAsc($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\Query orderDesc($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\Query andQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Navigation\Query whereQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Navigation\Query orQuery(\Mmi\Orm\Query $query)
 * @method \Cms\Orm\Navigation\Query resetOrder()
 * @method \Cms\Orm\Navigation\Query resetWhere()
 * @method \Cms\Orm\Navigation\Query\Field whereId()
 * @method \Cms\Orm\Navigation\Query\Field andFieldId()
 * @method \Cms\Orm\Navigation\Query\Field orFieldId()
 * @method \Cms\Orm\Navigation\Query orderAscId()
 * @method \Cms\Orm\Navigation\Query orderDescId()
 * @method \Cms\Orm\Navigation\Query groupById()
 * @method \Cms\Orm\Navigation\Query\Field whereLang()
 * @method \Cms\Orm\Navigation\Query\Field andFieldLang()
 * @method \Cms\Orm\Navigation\Query\Field orFieldLang()
 * @method \Cms\Orm\Navigation\Query orderAscLang()
 * @method \Cms\Orm\Navigation\Query orderDescLang()
 * @method \Cms\Orm\Navigation\Query groupByLang()
 * @method \Cms\Orm\Navigation\Query\Field whereParentId()
 * @method \Cms\Orm\Navigation\Query\Field andFieldParentId()
 * @method \Cms\Orm\Navigation\Query\Field orFieldParentId()
 * @method \Cms\Orm\Navigation\Query orderAscParentId()
 * @method \Cms\Orm\Navigation\Query orderDescParentId()
 * @method \Cms\Orm\Navigation\Query groupByParentId()
 * @method \Cms\Orm\Navigation\Query\Field whereOrder()
 * @method \Cms\Orm\Navigation\Query\Field andFieldOrder()
 * @method \Cms\Orm\Navigation\Query\Field orFieldOrder()
 * @method \Cms\Orm\Navigation\Query orderAscOrder()
 * @method \Cms\Orm\Navigation\Query orderDescOrder()
 * @method \Cms\Orm\Navigation\Query groupByOrder()
 * @method \Cms\Orm\Navigation\Query\Field whereModule()
 * @method \Cms\Orm\Navigation\Query\Field andFieldModule()
 * @method \Cms\Orm\Navigation\Query\Field orFieldModule()
 * @method \Cms\Orm\Navigation\Query orderAscModule()
 * @method \Cms\Orm\Navigation\Query orderDescModule()
 * @method \Cms\Orm\Navigation\Query groupByModule()
 * @method \Cms\Orm\Navigation\Query\Field whereController()
 * @method \Cms\Orm\Navigation\Query\Field andFieldController()
 * @method \Cms\Orm\Navigation\Query\Field orFieldController()
 * @method \Cms\Orm\Navigation\Query orderAscController()
 * @method \Cms\Orm\Navigation\Query orderDescController()
 * @method \Cms\Orm\Navigation\Query groupByController()
 * @method \Cms\Orm\Navigation\Query\Field whereAction()
 * @method \Cms\Orm\Navigation\Query\Field andFieldAction()
 * @method \Cms\Orm\Navigation\Query\Field orFieldAction()
 * @method \Cms\Orm\Navigation\Query orderAscAction()
 * @method \Cms\Orm\Navigation\Query orderDescAction()
 * @method \Cms\Orm\Navigation\Query groupByAction()
 * @method \Cms\Orm\Navigation\Query\Field whereParams()
 * @method \Cms\Orm\Navigation\Query\Field andFieldParams()
 * @method \Cms\Orm\Navigation\Query\Field orFieldParams()
 * @method \Cms\Orm\Navigation\Query orderAscParams()
 * @method \Cms\Orm\Navigation\Query orderDescParams()
 * @method \Cms\Orm\Navigation\Query groupByParams()
 * @method \Cms\Orm\Navigation\Query\Field whereLabel()
 * @method \Cms\Orm\Navigation\Query\Field andFieldLabel()
 * @method \Cms\Orm\Navigation\Query\Field orFieldLabel()
 * @method \Cms\Orm\Navigation\Query orderAscLabel()
 * @method \Cms\Orm\Navigation\Query orderDescLabel()
 * @method \Cms\Orm\Navigation\Query groupByLabel()
 * @method \Cms\Orm\Navigation\Query\Field whereTitle()
 * @method \Cms\Orm\Navigation\Query\Field andFieldTitle()
 * @method \Cms\Orm\Navigation\Query\Field orFieldTitle()
 * @method \Cms\Orm\Navigation\Query orderAscTitle()
 * @method \Cms\Orm\Navigation\Query orderDescTitle()
 * @method \Cms\Orm\Navigation\Query groupByTitle()
 * @method \Cms\Orm\Navigation\Query\Field whereKeywords()
 * @method \Cms\Orm\Navigation\Query\Field andFieldKeywords()
 * @method \Cms\Orm\Navigation\Query\Field orFieldKeywords()
 * @method \Cms\Orm\Navigation\Query orderAscKeywords()
 * @method \Cms\Orm\Navigation\Query orderDescKeywords()
 * @method \Cms\Orm\Navigation\Query groupByKeywords()
 * @method \Cms\Orm\Navigation\Query\Field whereDescription()
 * @method \Cms\Orm\Navigation\Query\Field andFieldDescription()
 * @method \Cms\Orm\Navigation\Query\Field orFieldDescription()
 * @method \Cms\Orm\Navigation\Query orderAscDescription()
 * @method \Cms\Orm\Navigation\Query orderDescDescription()
 * @method \Cms\Orm\Navigation\Query groupByDescription()
 * @method \Cms\Orm\Navigation\Query\Field whereUri()
 * @method \Cms\Orm\Navigation\Query\Field andFieldUri()
 * @method \Cms\Orm\Navigation\Query\Field orFieldUri()
 * @method \Cms\Orm\Navigation\Query orderAscUri()
 * @method \Cms\Orm\Navigation\Query orderDescUri()
 * @method \Cms\Orm\Navigation\Query groupByUri()
 * @method \Cms\Orm\Navigation\Query\Field whereVisible()
 * @method \Cms\Orm\Navigation\Query\Field andFieldVisible()
 * @method \Cms\Orm\Navigation\Query\Field orFieldVisible()
 * @method \Cms\Orm\Navigation\Query orderAscVisible()
 * @method \Cms\Orm\Navigation\Query orderDescVisible()
 * @method \Cms\Orm\Navigation\Query groupByVisible()
 * @method \Cms\Orm\Navigation\Query\Field whereHttps()
 * @method \Cms\Orm\Navigation\Query\Field andFieldHttps()
 * @method \Cms\Orm\Navigation\Query\Field orFieldHttps()
 * @method \Cms\Orm\Navigation\Query orderAscHttps()
 * @method \Cms\Orm\Navigation\Query orderDescHttps()
 * @method \Cms\Orm\Navigation\Query groupByHttps()
 * @method \Cms\Orm\Navigation\Query\Field whereAbsolute()
 * @method \Cms\Orm\Navigation\Query\Field andFieldAbsolute()
 * @method \Cms\Orm\Navigation\Query\Field orFieldAbsolute()
 * @method \Cms\Orm\Navigation\Query orderAscAbsolute()
 * @method \Cms\Orm\Navigation\Query orderDescAbsolute()
 * @method \Cms\Orm\Navigation\Query groupByAbsolute()
 * @method \Cms\Orm\Navigation\Query\Field whereIndependent()
 * @method \Cms\Orm\Navigation\Query\Field andFieldIndependent()
 * @method \Cms\Orm\Navigation\Query\Field orFieldIndependent()
 * @method \Cms\Orm\Navigation\Query orderAscIndependent()
 * @method \Cms\Orm\Navigation\Query orderDescIndependent()
 * @method \Cms\Orm\Navigation\Query groupByIndependent()
 * @method \Cms\Orm\Navigation\Query\Field whereNofollow()
 * @method \Cms\Orm\Navigation\Query\Field andFieldNofollow()
 * @method \Cms\Orm\Navigation\Query\Field orFieldNofollow()
 * @method \Cms\Orm\Navigation\Query orderAscNofollow()
 * @method \Cms\Orm\Navigation\Query orderDescNofollow()
 * @method \Cms\Orm\Navigation\Query groupByNofollow()
 * @method \Cms\Orm\Navigation\Query\Field whereBlank()
 * @method \Cms\Orm\Navigation\Query\Field andFieldBlank()
 * @method \Cms\Orm\Navigation\Query\Field orFieldBlank()
 * @method \Cms\Orm\Navigation\Query orderAscBlank()
 * @method \Cms\Orm\Navigation\Query orderDescBlank()
 * @method \Cms\Orm\Navigation\Query groupByBlank()
 * @method \Cms\Orm\Navigation\Query\Field whereDateStart()
 * @method \Cms\Orm\Navigation\Query\Field andFieldDateStart()
 * @method \Cms\Orm\Navigation\Query\Field orFieldDateStart()
 * @method \Cms\Orm\Navigation\Query orderAscDateStart()
 * @method \Cms\Orm\Navigation\Query orderDescDateStart()
 * @method \Cms\Orm\Navigation\Query groupByDateStart()
 * @method \Cms\Orm\Navigation\Query\Field whereDateEnd()
 * @method \Cms\Orm\Navigation\Query\Field andFieldDateEnd()
 * @method \Cms\Orm\Navigation\Query\Field orFieldDateEnd()
 * @method \Cms\Orm\Navigation\Query orderAscDateEnd()
 * @method \Cms\Orm\Navigation\Query orderDescDateEnd()
 * @method \Cms\Orm\Navigation\Query groupByDateEnd()
 * @method \Cms\Orm\Navigation\Query\Field whereActive()
 * @method \Cms\Orm\Navigation\Query\Field andFieldActive()
 * @method \Cms\Orm\Navigation\Query\Field orFieldActive()
 * @method \Cms\Orm\Navigation\Query orderAscActive()
 * @method \Cms\Orm\Navigation\Query orderDescActive()
 * @method \Cms\Orm\Navigation\Query groupByActive()
 * @method \Cms\Orm\Navigation\Query\Field andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\Query\Field where($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\Query\Field orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\Query\Join join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Navigation\Query\Join joinLeft($tableName, $targetTableName = null)
 * @method \Cms\Orm\Navigation\Record[] find()
 * @method \Cms\Orm\Navigation\Record findFirst()
 * @method \Cms\Orm\Navigation\Record findPk($value)
 */
//</editor-fold>
class Query extends \Mmi\Orm\Query {

	protected $_tableName = 'cms_navigation';

	/**
	 * @return \Cms\Orm\Navigation\Query
	 */
	public static function factory($daoClassName = null) {
		return new self($daoClassName);
	}

	/**
	 * 
	 * @return \Cms\Orm\Navigation\Query
	 */
	public static function lang() {
		if (!\Mmi\App\FrontController::getInstance()->getRequest()->lang) {
			return \Cms\Orm\Navigation\Query::factory();
		}
		return self::factory()
				->whereLang()->equals(\Mmi\App\FrontController::getInstance()->getRequest()->lang)
				->orFieldLang()->equals(null)
				->orderDescLang();
	}

	/**
	 * 
	 * @param string $uri
	 * @return \Cms\Orm\Navigation\Query
	 */
	public static function byArticleUri($uri) {
		return self::factory()
				->whereModule()->equals('cms')
				->andFieldController()->equals('article')
				->andFieldAction()->equals('index')
				->andFieldParams()->equals('uri=' . $uri);
	}

	/**
	 * 
	 * @param integer $parentId
	 * @return \Cms\Orm\Navigation\Query
	 */
	public static function byParentId($parentId) {
		return self::factory()
				->whereParentId()->equals($parentId);
	}

	/**
	 * 
	 * @param integer $parentId
	 * @return \Cms\Orm\Navigation\Query
	 */
	public static function descByParentId($parentId) {
		return self::byParentId($parentId)
				->orderDescOrder();
	}

}
