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
 * @method \Cms\Orm\Navigation\QueryField whereId()
 * @method \Cms\Orm\Navigation\QueryField andFieldId()
 * @method \Cms\Orm\Navigation\QueryField orFieldId()
 * @method \Cms\Orm\Navigation\Query orderAscId()
 * @method \Cms\Orm\Navigation\Query orderDescId()
 * @method \Cms\Orm\Navigation\Query groupById()
 * @method \Cms\Orm\Navigation\QueryField whereLang()
 * @method \Cms\Orm\Navigation\QueryField andFieldLang()
 * @method \Cms\Orm\Navigation\QueryField orFieldLang()
 * @method \Cms\Orm\Navigation\Query orderAscLang()
 * @method \Cms\Orm\Navigation\Query orderDescLang()
 * @method \Cms\Orm\Navigation\Query groupByLang()
 * @method \Cms\Orm\Navigation\QueryField whereParentId()
 * @method \Cms\Orm\Navigation\QueryField andFieldParentId()
 * @method \Cms\Orm\Navigation\QueryField orFieldParentId()
 * @method \Cms\Orm\Navigation\Query orderAscParentId()
 * @method \Cms\Orm\Navigation\Query orderDescParentId()
 * @method \Cms\Orm\Navigation\Query groupByParentId()
 * @method \Cms\Orm\Navigation\QueryField whereOrder()
 * @method \Cms\Orm\Navigation\QueryField andFieldOrder()
 * @method \Cms\Orm\Navigation\QueryField orFieldOrder()
 * @method \Cms\Orm\Navigation\Query orderAscOrder()
 * @method \Cms\Orm\Navigation\Query orderDescOrder()
 * @method \Cms\Orm\Navigation\Query groupByOrder()
 * @method \Cms\Orm\Navigation\QueryField whereModule()
 * @method \Cms\Orm\Navigation\QueryField andFieldModule()
 * @method \Cms\Orm\Navigation\QueryField orFieldModule()
 * @method \Cms\Orm\Navigation\Query orderAscModule()
 * @method \Cms\Orm\Navigation\Query orderDescModule()
 * @method \Cms\Orm\Navigation\Query groupByModule()
 * @method \Cms\Orm\Navigation\QueryField whereController()
 * @method \Cms\Orm\Navigation\QueryField andFieldController()
 * @method \Cms\Orm\Navigation\QueryField orFieldController()
 * @method \Cms\Orm\Navigation\Query orderAscController()
 * @method \Cms\Orm\Navigation\Query orderDescController()
 * @method \Cms\Orm\Navigation\Query groupByController()
 * @method \Cms\Orm\Navigation\QueryField whereAction()
 * @method \Cms\Orm\Navigation\QueryField andFieldAction()
 * @method \Cms\Orm\Navigation\QueryField orFieldAction()
 * @method \Cms\Orm\Navigation\Query orderAscAction()
 * @method \Cms\Orm\Navigation\Query orderDescAction()
 * @method \Cms\Orm\Navigation\Query groupByAction()
 * @method \Cms\Orm\Navigation\QueryField whereParams()
 * @method \Cms\Orm\Navigation\QueryField andFieldParams()
 * @method \Cms\Orm\Navigation\QueryField orFieldParams()
 * @method \Cms\Orm\Navigation\Query orderAscParams()
 * @method \Cms\Orm\Navigation\Query orderDescParams()
 * @method \Cms\Orm\Navigation\Query groupByParams()
 * @method \Cms\Orm\Navigation\QueryField whereLabel()
 * @method \Cms\Orm\Navigation\QueryField andFieldLabel()
 * @method \Cms\Orm\Navigation\QueryField orFieldLabel()
 * @method \Cms\Orm\Navigation\Query orderAscLabel()
 * @method \Cms\Orm\Navigation\Query orderDescLabel()
 * @method \Cms\Orm\Navigation\Query groupByLabel()
 * @method \Cms\Orm\Navigation\QueryField whereTitle()
 * @method \Cms\Orm\Navigation\QueryField andFieldTitle()
 * @method \Cms\Orm\Navigation\QueryField orFieldTitle()
 * @method \Cms\Orm\Navigation\Query orderAscTitle()
 * @method \Cms\Orm\Navigation\Query orderDescTitle()
 * @method \Cms\Orm\Navigation\Query groupByTitle()
 * @method \Cms\Orm\Navigation\QueryField whereKeywords()
 * @method \Cms\Orm\Navigation\QueryField andFieldKeywords()
 * @method \Cms\Orm\Navigation\QueryField orFieldKeywords()
 * @method \Cms\Orm\Navigation\Query orderAscKeywords()
 * @method \Cms\Orm\Navigation\Query orderDescKeywords()
 * @method \Cms\Orm\Navigation\Query groupByKeywords()
 * @method \Cms\Orm\Navigation\QueryField whereDescription()
 * @method \Cms\Orm\Navigation\QueryField andFieldDescription()
 * @method \Cms\Orm\Navigation\QueryField orFieldDescription()
 * @method \Cms\Orm\Navigation\Query orderAscDescription()
 * @method \Cms\Orm\Navigation\Query orderDescDescription()
 * @method \Cms\Orm\Navigation\Query groupByDescription()
 * @method \Cms\Orm\Navigation\QueryField whereUri()
 * @method \Cms\Orm\Navigation\QueryField andFieldUri()
 * @method \Cms\Orm\Navigation\QueryField orFieldUri()
 * @method \Cms\Orm\Navigation\Query orderAscUri()
 * @method \Cms\Orm\Navigation\Query orderDescUri()
 * @method \Cms\Orm\Navigation\Query groupByUri()
 * @method \Cms\Orm\Navigation\QueryField whereVisible()
 * @method \Cms\Orm\Navigation\QueryField andFieldVisible()
 * @method \Cms\Orm\Navigation\QueryField orFieldVisible()
 * @method \Cms\Orm\Navigation\Query orderAscVisible()
 * @method \Cms\Orm\Navigation\Query orderDescVisible()
 * @method \Cms\Orm\Navigation\Query groupByVisible()
 * @method \Cms\Orm\Navigation\QueryField whereDateStart()
 * @method \Cms\Orm\Navigation\QueryField andFieldDateStart()
 * @method \Cms\Orm\Navigation\QueryField orFieldDateStart()
 * @method \Cms\Orm\Navigation\Query orderAscDateStart()
 * @method \Cms\Orm\Navigation\Query orderDescDateStart()
 * @method \Cms\Orm\Navigation\Query groupByDateStart()
 * @method \Cms\Orm\Navigation\QueryField whereDateEnd()
 * @method \Cms\Orm\Navigation\QueryField andFieldDateEnd()
 * @method \Cms\Orm\Navigation\QueryField orFieldDateEnd()
 * @method \Cms\Orm\Navigation\Query orderAscDateEnd()
 * @method \Cms\Orm\Navigation\Query orderDescDateEnd()
 * @method \Cms\Orm\Navigation\Query groupByDateEnd()
 * @method \Cms\Orm\Navigation\QueryField whereAbsolute()
 * @method \Cms\Orm\Navigation\QueryField andFieldAbsolute()
 * @method \Cms\Orm\Navigation\QueryField orFieldAbsolute()
 * @method \Cms\Orm\Navigation\Query orderAscAbsolute()
 * @method \Cms\Orm\Navigation\Query orderDescAbsolute()
 * @method \Cms\Orm\Navigation\Query groupByAbsolute()
 * @method \Cms\Orm\Navigation\QueryField whereIndependent()
 * @method \Cms\Orm\Navigation\QueryField andFieldIndependent()
 * @method \Cms\Orm\Navigation\QueryField orFieldIndependent()
 * @method \Cms\Orm\Navigation\Query orderAscIndependent()
 * @method \Cms\Orm\Navigation\Query orderDescIndependent()
 * @method \Cms\Orm\Navigation\Query groupByIndependent()
 * @method \Cms\Orm\Navigation\QueryField whereNofollow()
 * @method \Cms\Orm\Navigation\QueryField andFieldNofollow()
 * @method \Cms\Orm\Navigation\QueryField orFieldNofollow()
 * @method \Cms\Orm\Navigation\Query orderAscNofollow()
 * @method \Cms\Orm\Navigation\Query orderDescNofollow()
 * @method \Cms\Orm\Navigation\Query groupByNofollow()
 * @method \Cms\Orm\Navigation\QueryField whereBlank()
 * @method \Cms\Orm\Navigation\QueryField andFieldBlank()
 * @method \Cms\Orm\Navigation\QueryField orFieldBlank()
 * @method \Cms\Orm\Navigation\Query orderAscBlank()
 * @method \Cms\Orm\Navigation\Query orderDescBlank()
 * @method \Cms\Orm\Navigation\Query groupByBlank()
 * @method \Cms\Orm\Navigation\QueryField whereHttps()
 * @method \Cms\Orm\Navigation\QueryField andFieldHttps()
 * @method \Cms\Orm\Navigation\QueryField orFieldHttps()
 * @method \Cms\Orm\Navigation\Query orderAscHttps()
 * @method \Cms\Orm\Navigation\Query orderDescHttps()
 * @method \Cms\Orm\Navigation\Query groupByHttps()
 * @method \Cms\Orm\Navigation\QueryField whereActive()
 * @method \Cms\Orm\Navigation\QueryField andFieldActive()
 * @method \Cms\Orm\Navigation\QueryField orFieldActive()
 * @method \Cms\Orm\Navigation\Query orderAscActive()
 * @method \Cms\Orm\Navigation\Query orderDescActive()
 * @method \Cms\Orm\Navigation\Query groupByActive()
 * @method \Cms\Orm\Navigation\QueryField andField($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\QueryField where($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\QueryField orField($fieldName, $tableName = null)
 * @method \Cms\Orm\Navigation\QueryJoin join($tableName, $targetTableName = null)
 * @method \Cms\Orm\Navigation\QueryJoin joinLeft($tableName, $targetTableName = null)
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
